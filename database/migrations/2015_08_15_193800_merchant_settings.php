<?php

use Illuminate\Database\Migrations\Migration;

class MerchantSettings extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('merchant', json_encode([
            'PayPalExpress' => ['enabled' => 0, 'username' => '', 'password' => '', 'signature' => ''],
            'Stripe'        => ['enabled' => 0, 'secretKey' => '', 'publishableKey' => ''],
            'Mollie'        => ['enabled' => 0, 'apiKey' => '']
        ]));
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
