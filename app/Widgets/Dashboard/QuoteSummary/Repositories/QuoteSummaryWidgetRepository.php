<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Widgets\Dashboard\QuoteSummary\Repositories;

use DB;
use FI\Modules\Quotes\Models\QuoteAmount;
use FI\Support\CurrencyFormatter;

class QuoteSummaryWidgetRepository
{
    public function getQuoteTotalDraft()
    {
        return CurrencyFormatter::format(QuoteAmount::join('quotes', 'quotes.id', '=', 'quote_amounts.quote_id')
            ->whereHas('quote', function ($q)
            {
                $q->draft();
                switch (config('fi.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetQuoteSummaryDashboardTotalsFromDate'), config('fi.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    public function getQuoteTotalSent()
    {
        return CurrencyFormatter::format(QuoteAmount::join('quotes', 'quotes.id', '=', 'quote_amounts.quote_id')
            ->whereHas('quote', function ($q)
            {
                $q->sent();
                switch (config('fi.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetQuoteSummaryDashboardTotalsFromDate'), config('fi.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    public function getQuoteTotalApproved()
    {
        return CurrencyFormatter::format(QuoteAmount::join('quotes', 'quotes.id', '=', 'quote_amounts.quote_id')
            ->whereHas('quote', function ($q)
            {
                $q->approved();
                switch (config('fi.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetQuoteSummaryDashboardTotalsFromDate'), config('fi.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    public function getQuoteTotalRejected()
    {
        return CurrencyFormatter::format(QuoteAmount::join('quotes', 'quotes.id', '=', 'quote_amounts.quote_id')
            ->whereHas('quote', function ($q)
            {
                $q->rejected();
                switch (config('fi.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('fi.widgetQuoteSummaryDashboardTotalsFromDate'), config('fi.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }
}