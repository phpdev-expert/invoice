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

use FI\Events\InvoiceModified;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Invoices\Models\InvoiceItem;
use FI\Support\Statuses\InvoiceStatuses;

class InvoiceCopyRepository
{
    public function __construct(
        ClientRepository $clientRepository,
        GroupRepository $groupRepository,
        InvoiceAmountRepository $invoiceAmountRepository
    )
    {
        $this->clientRepository        = $clientRepository;
        $this->groupRepository         = $groupRepository;
        $this->invoiceAmountRepository = $invoiceAmountRepository;
    }

    public function copyInvoice($fromInvoiceId, $clientListIdentifier, $createdAt, $dueAt, $groupId, $userId)
    {
        $client = $this->clientRepository->firstOrCreate($clientListIdentifier);

        $fromInvoice = Invoice::find($fromInvoiceId);

        $toInvoice = Invoice::create(
            [
                'client_id'         => $client->id,
                'created_at'        => $createdAt,
                'due_at'            => $dueAt,
                'group_id'          => $groupId,
                'number'            => $this->groupRepository->generateNumber($groupId),
                'user_id'           => $userId,
                'invoice_status_id' => InvoiceStatuses::getStatusId('draft'),
                'url_key'           => str_random(32),
                'currency_code'     => $fromInvoice->currency_code,
                'exchange_rate'     => $fromInvoice->exchange_rate,
                'terms'             => $fromInvoice->terms,
                'footer'            => $fromInvoice->footer,
                'template'          => $fromInvoice->template,
                'summary'           => $fromInvoice->summary
            ]
        );

        $this->invoiceAmountRepository->create($toInvoice->id);
        $this->groupRepository->incrementNextId($groupId);

        $items = InvoiceItem::where('invoice_id', '=', $fromInvoiceId)->get();

        foreach ($items as $item)
        {
            $newItem = InvoiceItem::create(
                [
                    'invoice_id'    => $toInvoice->id,
                    'name'          => $item->name,
                    'description'   => $item->description,
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                    'tax_rate_id'   => $item->tax_rate_id,
                    'tax_rate_2_id' => $item->tax_rate_2_id,
                    'display_order' => $item->display_order
                ]
            );
        }

        event(new InvoiceModified($toInvoice));

        return $toInvoice;
    }
}