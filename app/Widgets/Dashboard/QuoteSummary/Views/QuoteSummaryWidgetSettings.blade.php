@include('layouts._datepicker')

<script type="text/javascript">
    $(function () {
        $('#quote-dashboard-total-setting-from-date').datepicker({
            format: '{{ config('fi.datepickerFormat') }}',
            autoclose: true
        });
        $('#quote-dashboard-total-setting-to-date').datepicker({
            format: '{{ config('fi.datepickerFormat') }}',
            autoclose: true
        });

        $('#quote-dashboard-total-setting').change(function () {
            toggleWidgetQuoteDashboardTotalsDateRange($('#quote-dashboard-total-setting').val());
        });

        function toggleWidgetQuoteDashboardTotalsDateRange(val) {
            if (val == 'custom_date_range') {
                $('#div-quote-dashboard-totals-date-range').show();
            }
            else {
                $('#div-quote-dashboard-totals-date-range').hide();
            }
        }

        toggleWidgetQuoteDashboardTotalsDateRange($('#quote-dashboard-total-setting').val());
    });
</script>

<div class="form-group">
    <label>{{ trans('fi.dashboard_totals_option') }}: </label>
    {!! Form::select('setting_widgetQuoteSummaryDashboardTotals',
    ['year_to_date' => trans('fi.year_to_date'), 'this_quarter' => trans('fi.this_quarter'), 'all_time' => trans('fi.all_time'), 'custom_date_range' => trans('fi.custom_date_range')],
    config('fi.widgetQuoteSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'quote-dashboard-total-setting']) !!}
</div>

<div class="row" id="div-quote-dashboard-totals-date-range">
    <div class="col-md-2">
        <label>{{ trans('fi.from_date') }}:</label>
        {!! Form::text('setting_widgetQuoteSummaryDashboardTotalsFromDate', config('fi.widgetQuoteSummaryDashboardTotalsFromDate'), ['class' => 'form-control', 'id' => 'quote-dashboard-total-setting-from-date']) !!}
    </div>
    <div class="col-md-2">
        <label>{{ trans('fi.to_date') }}:</label>
        {!! Form::text('setting_widgetQuoteSummaryDashboardTotalsToDate', config('fi.widgetQuoteSummaryDashboardTotalsToDate'), ['class' => 'form-control', 'id' => 'quote-dashboard-total-setting-to-date']) !!}
    </div>
</div>