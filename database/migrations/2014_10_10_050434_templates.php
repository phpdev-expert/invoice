<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Templates extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table)
        {
            $table->string('invoice_template')->nullable();
            $table->string('quote_template')->nullable();
        });

        Schema::table('invoices', function (Blueprint $table)
        {
            $table->string('template')->nullable();
        });

        Schema::table('quotes', function (Blueprint $table)
        {
            $table->string('template')->nullable();
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
