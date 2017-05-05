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

use DB;
use FI\Modules\CustomFields\Repositories\QuoteCustomRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Quotes\Models\Quote;
use FI\Events\QuoteCreated;
use FI\Events\QuoteModified;
use FI\Support\BaseRepository;
use FI\Support\DateFormatter;
use FI\Support\Statuses\QuoteStatuses;

class QuoteRepository extends BaseRepository
{
    public function __construct(
        GroupRepository $groupRepository,
        QuoteCustomRepository $quoteCustomRepository,
        QuoteItemRepository $quoteItemRepository
    )
    {
        $this->groupRepository       = $groupRepository;
        $this->quoteCustomRepository = $quoteCustomRepository;
        $this->quoteItemRepository   = $quoteItemRepository;
    }

    public function paginateByStatus($status = 'all', $filter = null, $clientId = null)
    {
        $quote = Quote::select('quotes.*')
            ->join('clients', 'clients.id', '=', 'quotes.client_id')
            ->join('quote_amounts', 'quote_amounts.quote_id', '=', 'quotes.id')
            ->with($this->with);

        switch ($status)
        {
            case 'draft':
                $quote->draft();
                break;
            case 'sent':
                $quote->sent();
                break;
            case 'viewed':
                $quote->viewed();
                break;
            case 'approved':
                $quote->approved();
                break;
            case 'rejected':
                $quote->rejected();
                break;
            case 'canceled':
                $quote->canceled();
                break;
        }

        if ($filter)
        {
            $quote->keywords($filter);
        }

        if ($clientId)
        {
            $quote->where('client_id', $clientId);
        }

        return $quote->sortable(['created_at' => 'desc', 'number' => 'desc'])->paginate(config('fi.resultsPerPage'));
    }

    public function getRecent($limit)
    {
        return Quote::has('amount')->with(['amount', 'client'])->orderBy('created_at', 'DESC')->limit($limit)->get();
    }

    public function find($id)
    {
        return Quote::with($this->with)->find($id);
    }

    public function findIdByNumber($number)
    {
        if ($quote = Quote::where('number', $number)->first())
        {
            return $quote->id;
        }

        return null;
    }

    public function findByUrlKey($urlKey)
    {
        return Quote::where('url_key', $urlKey)->first();
    }

    public function create($input, $client)
    {
        $groupId   = (isset($input['group_id'])) ? $input['group_id'] : config('fi.quoteGroup');
        $createdAt = (isset($input['created_at'])) ? DateFormatter::unformat($input['created_at']) : date('Y-m-d');
        $summary   = (isset($input['summary']) ? $input['summary'] : '');

        $quote = Quote::create(
            ['client_id'       => $client->id,
             'created_at'      => $createdAt,
             'expires_at'      => DateFormatter::incrementDateByDays($createdAt, config('fi.quotesExpireAfter')),
             'group_id'        => $groupId,
             'number'          => $this->groupRepository->generateNumber($groupId),
             'user_id'         => $input['user_id'],
             'quote_status_id' => QuoteStatuses::getStatusId('draft'),
             'url_key'         => str_random(32),
             'terms'           => config('fi.quoteTerms'),
             'footer'          => config('fi.quoteFooter'),
             'currency_code'   => $client->currency_code,
             'exchange_rate'   => '',
             'template'        => $client->quote_template,
             'summary'         => $summary
            ]
        );

        event(new QuoteCreated($quote));

        return $quote;
    }

    public function update($input, $id)
    {
        $custom = (array)json_decode($input['custom']);

        unset($input['custom']);

        $quoteInput = [
            'number'          => $input['number'],
            'created_at'      => DateFormatter::unformat($input['created_at']),
            'expires_at'      => DateFormatter::unformat($input['expires_at']),
            'quote_status_id' => $input['quote_status_id'],
            'terms'           => $input['terms'],
            'footer'          => $input['footer'],
            'currency_code'   => $input['currency_code'],
            'exchange_rate'   => $input['exchange_rate'],
            'template'        => $input['template'],
            'summary'         => $input['summary']
        ];

        $quote = Quote::find($id);

        $quote->fill($quoteInput);

        $quote->save();

        $this->quoteCustomRepository->save($custom, $id);

        $this->quoteItemRepository->saveItems(
            json_decode($input['items'], true),
            isset($input['apply_exchange_rate']),
            $input['exchange_rate']);

        event(new QuoteModified($quote));

        return $quote;
    }

    public function updateRaw($input, $id)
    {
        $quote = Quote::find($id);

        $quote->fill($input);

        $quote->save();

        return $quote;
    }

    public function approve($urlKey)
    {
        $quote = $this->findByUrlKey($urlKey);

        $quote->quote_status_id = QuoteStatuses::getStatusId('approved');

        $quote->save();

        return $quote;
    }

    public function reject($urlKey)
    {
        $quote = $this->findByUrlKey($urlKey);

        $quote->quote_status_id = QuoteStatuses::getStatusId('rejected');

        $quote->save();

        return $quote;
    }

    public function delete($id)
    {
        Quote::destroy($id);
    }
}