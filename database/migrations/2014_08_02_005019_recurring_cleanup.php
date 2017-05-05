<?php

use FI\Modules\Invoices\Models\RecurringInvoice;
use Illuminate\Database\Migrations\Migration;

class RecurringCleanup extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Previously, recurring invoices were not being deleted when the base
        // invoice was deleted. This was causing recurring invoices not to
        // generate properly. Deleting a base invoice now deletes the recurring
        // invoice, but we need to clean up any orphaned records still in the
        // system from before the bug was fixed.

        RecurringInvoice::whereNotIn('invoice_id', function ($query)
        {
            $query->select('id')->from('invoices');
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
