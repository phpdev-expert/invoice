<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Repositories;

use DB;
use FI\Events\InvoiceCreated;
use FI\Events\InvoiceModified;
use FI\Modules\CustomFields\Repositories\InvoiceCustomRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Invoices\Models\Invoice;
use FI\Support\BaseRepository;
use FI\Support\DateFormatter;

class InvoiceRepository extends BaseRepository
{
    public function __construct(
        GroupRepository $groupRepository,
        InvoiceCustomRepository $invoiceCustomRepository,
        InvoiceItemRepository $invoiceItemRepository,
        RecurringInvoiceRepository $recurringInvoiceRepository
    )
    {
        $this->groupRepository            = $groupRepository;
        $this->invoiceCustomRepository    = $invoiceCustomRepository;
        $this->invoiceItemRepository      = $invoiceItemRepository;
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
    }

    public function paginateByStatus($status = 'all', $filter = null, $clientId = null)
    {
        $invoice = Invoice::select('invoices.*')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->join('invoice_amounts', 'invoice_amounts.invoice_id', '=', 'invoices.id')
            ->with($this->with);

        switch ($status)
        {
            case 'draft':
                $invoice->draft();
                break;
            case 'sent':
                $invoice->sent();
                break;
            case 'viewed':
                $invoice->viewed();
                break;
            case 'paid':
                $invoice->paid();
                break;
            case 'canceled':
                $invoice->canceled();
                break;
            case 'overdue':
                $invoice->overdue();
                break;
        }

        if ($filter)
        {
            $invoice->keywords($filter);
        }

        if ($clientId)
        {
            $invoice->where('client_id', $clientId);
        }

        return $invoice->sortable(['created_at' => 'desc', 'number' => 'desc'])->paginate(config('fi.resultsPerPage'));
    }

    public function getRecent($limit)
    {
        return Invoice::has('amount')->with(['amount', 'client'])->orderBy('created_at', 'DESC')->limit($limit)->get();
    }

    public function getRecentOverdue($limit)
    {
        return Invoice::has('amount')->overdue()->with(['amount', 'client'])->orderBy('due_at')->limit($limit)->get();
    }

    public function find($id)
    {
        return Invoice::with($this->with)->find($id);
    }

    public function findIdByNumber($number)
    {
        if ($invoice = Invoice::where('number', $number)->first())
        {
            return $invoice->id;
        }

        return null;
    }

    public function findByUrlKey($urlKey)
    {
        return Invoice::where('url_key', $urlKey)->first();
    }

    public function create($input, $client)
    {
        $groupId   = (isset($input['group_id'])) ? $input['group_id'] : config('fi.invoiceGroup');
        $createdAt = (isset($input['created_at'])) ? DateFormatter::unformat($input['created_at']) : date('Y-m-d');
        $summary   = (isset($input['summary']) ? $input['summary'] : '');

        $invoice = Invoice::create(
            ['client_id'         => $client->id,
             'created_at'        => $createdAt,
             'due_at'            => DateFormatter::incrementDateByDays($createdAt, config('fi.invoicesDueAfter')),
             'group_id'          => $groupId,
             'number'            => $this->groupRepository->generateNumber($groupId),
             'user_id'           => $input['user_id'],
             'invoice_status_id' => 1,
             'url_key'           => str_random(32),
             'terms'             => config('fi.invoiceTerms'),
             'footer'            => config('fi.invoiceFooter'),
             'currency_code'     => $client->currency_code,
             'exchange_rate'     => '',
             'template'          => $client->invoice_template,
             'summary'           => $summary
            ]
        );

        event(new InvoiceCreated($invoice));

        if (isset($input['recurring']) and $input['recurring'] == 1)
        {
            $this->recurringInvoiceRepository->create($input, $invoice->id);
        }

        return $invoice;
    }

    public function update($input, $id)
    {
        $custom = (array)json_decode($input['custom']);

        unset($input['custom']);

        $invoiceInput = [
            'number'            => $input['number'],
            'created_at'        => DateFormatter::unformat($input['created_at']),
            'due_at'            => DateFormatter::unformat($input['due_at']),
            'invoice_status_id' => $input['invoice_status_id'],
            'terms'             => $input['terms'],
            'footer'            => $input['footer'],
            'currency_code'     => $input['currency_code'],
            'exchange_rate'     => $input['exchange_rate'],
            'template'          => $input['template'],
            'summary'           => $input['summary']
        ];

        $invoice = Invoice::find($id);

        $invoice->fill($invoiceInput);

        $invoice->save();

        $this->invoiceCustomRepository->save($custom, $id);

        $this->invoiceItemRepository->saveItems(
            json_decode($input['items'], true),
            isset($input['apply_exchange_rate']),
            $input['exchange_rate']
        );

        event(new InvoiceModified($invoice));

        return $invoice;
    }

    public function updateRaw($input, $id)
    {
        $invoice = Invoice::find($id);

        $invoice->fill($input);

        $invoice->save();

        return $invoice;
    }

    public function delete($id)
    {
        Invoice::destroy($id);
    }
}