<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DashboardSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = App::make('FI\Modules\Settings\Repositories\SettingRepository');

        $setting->save('widgetEnabledInvoiceSummary', 1);
        $setting->save('widgetInvoiceSummaryDashboardTotals', 'year_to_date');
        $setting->save('widgetEnabledQuoteSummary', 1);
        $setting->save('widgetQuoteSummaryDashboardTotals', 'year_to_date');
        $setting->save('widgetDisplayOrderInvoiceSummary', 1);
        $setting->save('widgetColumnWidthInvoiceSummary', 6);
        $setting->save('widgetDisplayOrderQuoteSummary', 2);
        $setting->save('widgetColumnWidthQuoteSummary', 6);
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
