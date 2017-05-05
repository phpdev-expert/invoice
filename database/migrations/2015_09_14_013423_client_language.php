<?php

use FI\Modules\Clients\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ClientLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table)
        {
            $table->string('language')->nullable();
        });

        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        Client::where('language', null)->update(['language' => $setting->get('language')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table)
        {
            $table->dropColumn('language');
        });
    }
}
