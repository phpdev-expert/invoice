<?php

use Illuminate\Database\Migrations\Migration;

class AddressFormatSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('addressFormat', "{{ address }}\r\n{{ city }}, {{ state }} {{ postal_code }}");
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
