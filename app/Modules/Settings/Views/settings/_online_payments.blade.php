<h4 style="font-weight: bold; clear: both;">PayPal</h4>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.enabled') }}: </label>
            {!! Form::select('setting_merchant[PayPalExpress][enabled]', [0=>trans('fi.no'),1=>trans('fi.yes')], $merchant['PayPalExpress']['enabled'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.merchant_api_username') }}: </label>
            {!! Form::text('setting_merchant[PayPalExpress][username]', $merchant['PayPalExpress']['username'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.merchant_api_password') }}: </label>
            {!! Form::text('setting_merchant[PayPalExpress][password]', $merchant['PayPalExpress']['password'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.merchant_signature') }}: </label>
            {!! Form::text('setting_merchant[PayPalExpress][signature]', $merchant['PayPalExpress']['signature'], ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<h4 style="font-weight: bold; clear: both;">Stripe</h4>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.enabled') }}: </label>
            {!! Form::select('setting_merchant[Stripe][enabled]', [0=>trans('fi.no'),1=>trans('fi.yes')], $merchant['Stripe']['enabled'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.merchant_secret_key') }}: </label>
            {!! Form::text('setting_merchant[Stripe][secretKey]', $merchant['Stripe']['secretKey'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.merchant_publishable_key') }}: </label>
            {!! Form::text('setting_merchant[Stripe][publishableKey]', $merchant['Stripe']['publishableKey'], ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<h4 style="font-weight: bold; clear: both;">Mollie</h4>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>{{ trans('fi.enabled') }}: </label>
            {!! Form::select('setting_merchant[Mollie][enabled]', [0=>trans('fi.no'),1=>trans('fi.yes')], $merchant['Mollie']['enabled'], ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <label>{{ trans('fi.merchant_api_key') }}: </label>
            {!! Form::text('setting_merchant[Mollie][apiKey]', $merchant['Mollie']['apiKey'], ['class' => 'form-control']) !!}
        </div>
    </div>
</div>