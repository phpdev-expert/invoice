<?php

use Illuminate\Database\Migrations\Migration;

class Version220 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('overdueInvoiceEmailBody', '<p>This is a reminder to let you know your invoice from {{ $invoice->user->name }} for {{ $invoice->amount->formatted_total }} is overdue. Click the link below to view the invoice:</p>' . "\r\n\r\n" . '<p><a href="{{ $invoice->public_url }}">{{ $invoice->public_url }}</a></p>');
        $setting->save('invoiceEmailBody', '<p>To view your invoice from {{ $invoice->user->name }} for {{ $invoice->amount->formatted_total }}, click the link below:</p>' . "\r\n\r\n" . '<p><a href="{{ $invoice->public_url }}">{{ $invoice->public_url }}</a></p>');
        $setting->save('quoteEmailBody', '<p>To view your quote from {{ $quote->user->name }} for {{ $quote->amount->formatted_total }}, click the link below:</p>' . "\r\n\r\n" . '<p><a href="{{ $quote->public_url }}">{{ $quote->public_url }}</a></p>');
        $setting->save('convertQuoteWhenApproved', 1);
        $setting->save('paperOrientation', 'portrait');
        $setting->save('paperSize', 'letter');
        $setting->save('version', '2.2.0');
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
