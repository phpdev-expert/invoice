<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuoteStatusChangeSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('quoteApprovedEmailBody', '<p><a href="{{ $quote->public_url }}">Quote #{{ $quote->number }}</a> has been APPROVED.</p>');
        $setting->save('quoteRejectedEmailBody', '<p><a href="{{ $quote->public_url }}">Quote #{{ $quote->number }}</a> has been REJECTED.</p>');
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
