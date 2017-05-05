@section('javascript')
    @parent
    <script type="text/javascript">
        $().ready(function () {
            $('#btn-check-update').click(function () {
                $.get("{{ route('settings.updateCheck') }}")
                        .done(function (response) {
                            alert(response.message);
                        })
                        .fail(function (response) {
                            alert("{{ trans('fi.unknown_error') }}");
                        });
            });
        });
    </script>
@stop

<div class="row">

    <div class="col-md-8">
        <div class="form-group">
            <label>{{ trans('fi.header_title_text') }}: </label>
            {!! Form::text('setting_headerTitleText', config('fi.headerTitleText'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.version') }}: </label>

            <div class="input-group">
                {!! Form::text('version', config('fi.version'), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="input-group-btn">
					<button class="btn btn-default" id="btn-check-update"
                            type="button">{{ trans('fi.check_for_update') }}</button>
				</span>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.skin') }}: </label>
            {!! Form::select('setting_skin', $skins, config('fi.skin'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.language') }}: </label>
            {!! Form::select('setting_language', $languages, config('fi.language'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.date_format') }}: </label>
            {!! Form::select('setting_dateFormat', $dateFormats, config('fi.dateFormat'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.timezone') }}: </label>
            {!! Form::select('setting_timezone', $timezones, config('fi.timezone'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ trans('fi.display_client_unique_name') }}: </label>
                    {!! Form::select('setting_displayClientUniqueName', $clientUniqueNameOptions, config('fi.displayClientUniqueName'), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ trans('fi.base_currency') }}: </label>
                    {!! Form::select('setting_baseCurrency', $currencies, config('fi.baseCurrency'), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ trans('fi.exchange_rate_mode') }}: </label>
                    {!! Form::select('setting_exchangeRateMode', $exchangeRateModes, config('fi.exchangeRateMode'), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('fi.address_format') }}: </label>
            {!! Form::textarea('setting_addressFormat', config('fi.addressFormat'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('fi.logo') }}: </label>
            @if ($invoiceLogoImg)
                <p>{!! $invoiceLogoImg !!}</p>
                <a href="{{ route('settings.logo.delete') }}">{{ trans('fi.remove_logo') }}</a>
            @endif
            {!! Form::file('logo') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('fi.results_per_page') }}:</label>
            {!! Form::select('setting_resultsPerPage', $resultsPerPage, config('fi.resultsPerPage'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>