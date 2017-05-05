<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Tasks\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Invoices\Repositories\RecurringInvoiceRepository;
use FI\Modules\MailQueue\Support\MailQueue;

class TaskController extends Controller
{
    public function __construct(MailQueue $mailQueue, RecurringInvoiceRepository $recurringInvoiceRepository)
    {
        $this->mailQueue                  = $mailQueue;
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
    }

    public function run()
    {
        $this->mailQueue->send();

        if (date('H') == 0 and date('i') == 0 or app()->environment('testing'))
        {
            $this->mailQueue->queueOverdueInvoices();

            $this->recurringInvoiceRepository->recurInvoices();
        }

    }
}