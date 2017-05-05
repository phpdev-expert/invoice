<?php

use FI\Modules\Clients\Models\Client;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Quotes\Models\Quote;
use Illuminate\Database\Migrations\Migration;

class DefaultTemplates extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        Client::whereNull('invoice_template')->update(['invoice_template' => $setting->get('invoiceTemplate')]);
        Client::whereNull('quote_template')->update(['quote_template' => $setting->get('quoteTemplate')]);

        $invoiceSubquery = '(' . DB::table('clients')->select('invoice_template')->where('clients.id', DB::raw(DB::getTablePrefix() . 'invoices.id'))->toSql() . ')';
        $quoteSubquery   = '(' . DB::table('clients')->select('quote_template')->where('clients.id', DB::raw(DB::getTablePrefix() . 'quotes.id'))->toSql() . ')';

        Invoice::whereNull('template')->update(['template' => DB::raw($invoiceSubquery)]);
        Quote::whereNull('template')->update(['template' => DB::raw($quoteSubquery)]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
