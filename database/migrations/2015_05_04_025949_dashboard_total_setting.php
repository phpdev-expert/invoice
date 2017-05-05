<?php

use Illuminate\Database\Migrations\Migration;

class DashboardTotalSetting extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App::make('FI\Modules\Settings\Repositories\SettingRepository')->save('dashboardTotals', 'year_to_date');
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
