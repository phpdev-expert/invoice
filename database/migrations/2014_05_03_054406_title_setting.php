<?php

use Illuminate\Database\Migrations\Migration;

class TitleSetting extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('headerTitleText', 'FusionInvoice');
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
