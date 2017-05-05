<?php

use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Quotes\Models\Quote;
use Illuminate\Database\Migrations\Migration;

class Update extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // A bug when deleting quotes & invoices in previous release deleted
        // amount records but not necessarily the parent quote or invoice, so
        // let's get those out now.

        Invoice::whereNotIn('id', function ($query)
        {
            $query->select('invoice_id')->from('invoice_amounts');
        })->delete();

        Quote::whereNotIn('id', function ($query)
        {
            $query->select('invoice_id')->from('quote_amounts');
        })->delete();
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