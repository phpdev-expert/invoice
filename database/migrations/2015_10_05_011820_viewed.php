<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Viewed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function(Blueprint $table)
        {
            $table->boolean('viewed')->default(0);
        });

        Schema::table('quotes', function(Blueprint $table)
        {
            $table->boolean('viewed')->default(0);
        });

        DB::table('invoices')->whereIn('id', function($query)
        {
            $query->select('audit_id')->from('activities')->where('audit_type', 'FI\Modules\Invoices\Models\Invoice');
        })->update(['viewed' => 1]);

        DB::table('quotes')->whereIn('id', function($query)
        {
            $query->select('audit_id')->from('activities')->where('audit_type', 'FI\Modules\Quotes\Models\Quote');
        })->update(['viewed' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function(Blueprint $table)
        {
            $table->dropColumn('viewed');
        });

        Schema::table('quotes', function(Blueprint $table)
        {
            $table->dropColumn('viewed');
        });
    }
}