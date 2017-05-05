<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Amounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_amounts', function (Blueprint $table)
        {
            $table->dropColumn('tax_total');
        });

        Schema::table('quote_amounts', function (Blueprint $table)
        {
            $table->dropColumn('tax_total');
        });

        Schema::table('invoice_amounts', function (Blueprint $table)
        {
            $table->renameColumn('item_subtotal', 'subtotal');
            $table->renameColumn('item_tax_total', 'tax');
        });

        Schema::table('quote_amounts', function (Blueprint $table)
        {
            $table->renameColumn('item_subtotal', 'subtotal');
            $table->renameColumn('item_tax_total', 'tax');
        });

        Schema::table('invoice_item_amounts', function (Blueprint $table)
        {
            $table->renameColumn('tax_total', 'tax');
        });

        Schema::table('quote_item_amounts', function (Blueprint $table)
        {
            $table->renameColumn('tax_total', 'tax');
        });
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
