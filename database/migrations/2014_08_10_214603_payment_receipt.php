<?php

use Illuminate\Database\Migrations\Migration;

class PaymentReceipt extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('paymentReceiptBody', '<p>Thank you! Your payment of {{ $payment->formatted_amount }} has been applied to Invoice #{{ $payment->invoice->number }}.</p>');
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
