<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class InvoiceQuoteSummary extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table)
        {
            $table->string('summary', 100)->nullable();
        });

        Schema::table('quotes', function (Blueprint $table)
        {
            $table->string('summary', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table)
        {
            $table->dropColumn('summary');
        });

        Schema::table('quotes', function (Blueprint $table)
        {
            $table->dropColumn('summary');
        });
    }
}
