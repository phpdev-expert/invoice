<?php

use Illuminate\Database\Migrations\Migration;

class Profileimagedriver extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('profileImageDriver', 'Gravatar');
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
