<?php

use Illuminate\Database\Migrations\Migration;

class PdfDriver extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('pdfDriver', 'domPDF');
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
