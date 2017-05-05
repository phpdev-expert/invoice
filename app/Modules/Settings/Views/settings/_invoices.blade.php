<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.default_invoice_template') }}: </label>
            {!! Form::select('setting_invoiceTemplate', $invoiceTemplates, config('fi.invoiceTemplate'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.default_group') }}: </label>
            {!! Form::select('setting_invoiceGroup', $groups, config('fi.invoiceGroup'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.invoices_due_after') }}: </label>
            {!! Form::text('setting_invoicesDueAfter', config('fi.invoicesDueAfter'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="form-group">
    <label>{{ trans('fi.default_terms') }}: </label>
    {!! Form::textarea('setting_invoiceTerms', config('fi.invoiceTerms'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_footer') }}: </label>
    {!! Form::textarea('setting_invoiceFooter', config('fi.invoiceFooter'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('fi.automatic_email_on_recur') }}: </label>
            {!! Form::select('setting_automaticEmailOnRecur', ['0' => trans('fi.no'), '1' => trans('fi.yes')], config('fi.automaticEmailOnRecur'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('fi.automatic_email_payment_receipts') }}: </label>
            {!! Form::select('setting_automaticEmailPaymentReceipts', ['0' => trans('fi.no'), '1' => trans('fi.yes')], config('fi.automaticEmailPaymentReceipts'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label>{{ trans('fi.online_payment_method') }}: </label>
    {!! Form::select('setting_onlinePaymentMethod', $paymentMethods, config('fi.onlinePaymentMethod'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.recalculate_invoices') }}: </label><br>
    <button type="button" class="btn btn-default" id="btn-recalculate-invoices"
            data-loading-text="{{ trans('fi.recalculating_wait') }}">{{ trans('fi.recalculate') }}</button>
    <p class="help-block">{{ trans('fi.recalculate_help_text') }}</p>
</div>