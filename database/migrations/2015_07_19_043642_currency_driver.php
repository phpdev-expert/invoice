<?php

use Illuminate\Database\Migrations\Migration;

class CurrencyDriver extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('currencyConversionDriver', 'YQLCurrencyConverter');
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
