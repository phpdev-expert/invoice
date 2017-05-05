<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\MailQueue\Support;

use Carbon\Carbon;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Support\Parser;
use FI\Support\PDF\PDFFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailQueue
{
    public function __construct()
    {
        $this->mailQueueRepository = new MailQueueRepository();
    }

    public function send()
    {
        foreach ($this->mailQueueRepository->getUnsent() as $mail)
        {
            $this->sendMail(
                $mail->from,
                $mail->to,
                $mail->cc,
                $mail->bcc,
                $mail->subject,
                $mail->body,
                $this->getAttachmentPath($mail)
            );

            $mail->sent = 1;
            $mail->save();
        }
    }

    public function queueOverdueInvoices()
    {
        $days = config('fi.overdueInvoiceReminderFrequency');

        if ($days)
        {
            $days = explode(',', $days);

            foreach ($days as $daysAgo)
            {
                $daysAgo = trim($daysAgo);

                if (is_numeric($daysAgo))
                {
                    $daysAgo = intval($daysAgo);

                    $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');

                    $invoices = Invoice::dueOnDate($date)->get();

                    Log::info('FI::MailQueue - Invoices found due ' . $daysAgo . ' days ago on ' . $date . ': ' . $invoices->count());

                    foreach ($invoices as $invoice)
                    {
                        $this->mailQueueRepository->create($invoice, [
                            'to'         => $invoice->client->email,
                            'cc'         => config('fi.mailDefaultCc'),
                            'bcc'        => config('fi.mailDefaultBcc'),
                            'subject'    => trans('fi.overdue_invoice_reminder') . ': ' . trans('fi.invoice') . ' #' . $invoice->number,
                            'body'       => Parser::parse($invoice, config('fi.overdueInvoiceEmailBody')),
                            'attach_pdf' => config('fi.attachPdf')
                        ]);
                    }
                }
                else
                {
                    Log::info('FI::MailQueue - Invalid overdue indicator: ' . $daysAgo);
                }
            }
        }

    }

    private function getAttachmentPath($mail)
    {
        if ($mail->attach_pdf)
        {
            $object = $mail->mailable;

            $pdfPath = base_path() . '/storage/' . $object->pdf_filename;

            $pdf = PDFFactory::create();

            $pdf->save($object->html, $pdfPath);

            return $pdfPath;
        }

        return null;
    }

    private function sendMail($from, $to, $cc, $bcc, $subject, $body, $attachmentPath = null)
    {
        Mail::send(['templates.emails.html', 'templates.emails.text'], ['body' => $body], function ($message) use ($from, $to, $cc, $bcc, $subject, $attachmentPath)
        {
            $from = json_decode($from, true);
            $to   = json_decode($to, true);
            $cc   = json_decode($cc, true);
            $bcc  = json_decode($bcc, true);

            $message->from($from['email'], $from['name']);
            $message->subject($subject);

            foreach ($to as $toRecipient)
            {
                $message->to(trim($toRecipient));
            }

            foreach ($cc as $ccRecipient)
            {
                if ($ccRecipient !== '')
                {
                    $message->cc(trim($ccRecipient));
                }

            }

            foreach ($bcc as $bccRecipient)
            {
                if ($bccRecipient !== '')
                {
                    $message->bcc(trim($bccRecipient));
                }
            }

            if ($attachmentPath)
            {
                $message->attach($attachmentPath);
            }
        });

        if ($attachmentPath and file_exists($attachmentPath))
        {
            unlink($attachmentPath);
        }
    }
}