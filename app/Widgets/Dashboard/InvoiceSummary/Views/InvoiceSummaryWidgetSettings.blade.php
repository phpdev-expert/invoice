@include('layouts._datepicker')

<script type="text/javascript">
    $(function () {
        $('#invoice-dashboard-total-setting-from-date').datepicker({
            format: '{{ config('fi.datepickerFormat') }}',
            autoclose: true
        });
        $('#invoice-dashboard-total-setting-to-date').datepicker({
            format: '{{ config('fi.datepickerFormat') }}',
            autoclose: true
        });

        $('#invoice-dashboard-total-setting').change(function () {
            toggleWidgetInvoiceDashboardTotalsDateRange($('#invoice-dashboard-total-setting').val());
        });

        function toggleWidgetInvoiceDashboardTotalsDateRange(val) {
            if (val == 'custom_date_range') {
                $('#div-invoice-dashboard-totals-date-range').show();
            }
            else {
                $('#div-invoice-dashboard-totals-date-range').hide();
            }
        }

        toggleWidgetInvoiceDashboardTotalsDateRange($('#invoice-dashboard-total-setting').val());
    });
</script>

<div class="form-group">
    <label>{{ trans('fi.dashboard_totals_option') }}: </label>
    {!! Form::select('setting_widgetInvoiceSummaryDashboardTotals',
    ['year_to_date' => trans('fi.year_to_date'), 'this_quarter' => trans('fi.this_quarter'), 'all_time' => trans('fi.all_time'), 'custom_date_range' => trans('fi.custom_date_range')],
    config('fi.widgetInvoiceSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'invoice-dashboard-total-setting']) !!}
</div>

<div class="row" id="div-invoice-dashboard-totals-date-range">
    <div class="col-md-2">
        <label>{{ trans('fi.from_date') }}:</label>
        {!! Form::text('setting_widgetInvoiceSummaryDashboardTotalsFromDate', config('fi.widgetInvoiceSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'invoice-dashboard-total-setting-from-date']) !!}
    </div>
    <div class="col-md-2">
        <label>{{ trans('fi.to_date') }}:</label>
        {!! Form::text('setting_widgetInvoiceSummaryDashboardTotalsToDate', config('fi.widgetInvoiceSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'invoice-dashboard-total-setting-to-date']) !!}
    </div>
</div>