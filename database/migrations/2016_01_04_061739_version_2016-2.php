<?php

use Illuminate\Database\Migrations\Migration;

class Version20162 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('version', '2016-2');
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
