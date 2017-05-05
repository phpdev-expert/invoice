<?php

use Illuminate\Database\Migrations\Migration;

class QuoteTermOption extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('convertQuoteTerms', 'quote');
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
