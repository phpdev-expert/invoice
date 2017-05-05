<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Repositories;

use FI\Events\QuoteModified;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Quotes\Models\Quote;
use FI\Modules\Quotes\Models\QuoteItem;
use FI\Support\Statuses\QuoteStatuses;

class QuoteCopyRepository
{
    public function __construct(
        ClientRepository $clientRepository,
        GroupRepository $groupRepository,
        QuoteAmountRepository $quoteAmountRepository
    )
    {
        $this->clientRepository      = $clientRepository;
        $this->groupRepository       = $groupRepository;
        $this->quoteAmountRepository = $quoteAmountRepository;
    }

    public function copyQuote($fromQuoteId, $clientListIdentifier, $createdAt, $expiresAt, $groupId, $userId)
    {
        $fromQuote = Quote::find($fromQuoteId);
        $client    = $this->clientRepository->firstOrCreate($clientListIdentifier);

        $toQuote = Quote::create(
            [
                'client_id'       => $client->id,
                'created_at'      => $createdAt,
                'expires_at'      => $expiresAt,
                'group_id'        => $groupId,
                'number'          => $this->groupRepository->generateNumber($groupId),
                'user_id'         => $userId,
                'quote_status_id' => QuoteStatuses::getStatusId('draft'),
                'url_key'         => str_random(32),
                'currency_code'   => $fromQuote->currency_code,
                'exchange_rate'   => $fromQuote->exchange_rate,
                'terms'           => $fromQuote->terms,
                'footer'          => $fromQuote->footer,
                'template'        => $fromQuote->template,
                'summary'         => $fromQuote->summary
            ]
        );

        $this->quoteAmountRepository->create($toQuote->id);
        $this->groupRepository->incrementNextId($groupId);

        $items = QuoteItem::where('quote_id', '=', $fromQuoteId)->get();

        foreach ($items as $item)
        {
            $newItem = QuoteItem::create(
                [
                    'quote_id'      => $toQuote->id,
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

        event(new QuoteModified($toQuote));

        return $toQuote;
    }
}