<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddClientListIdentifier extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table)
        {
            $table->string('unique_name')->nullable();

            $table->index('unique_name');
        });

        DB::table('clients')->update(['unique_name' => DB::raw('name')]);

        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('displayClientUniqueName', '0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

}