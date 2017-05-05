<?php

use Illuminate\Database\Migrations\Migration;

class DashboardWidgetSettings extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('widgetEnabledInvoiceSummary', '1');
        $setting->save('widgetInvoiceSummaryDashboardTotals', 'year_to_date');

        $setting->save('widgetEnabledQuoteSummary', '1');
        $setting->save('widgetQuoteSummaryDashboardTotals', 'year_to_date');

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
