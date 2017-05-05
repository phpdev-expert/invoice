<?php

use Illuminate\Database\Migrations\Migration;

class ResultsPerPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('resultsPerPage', 15);
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
