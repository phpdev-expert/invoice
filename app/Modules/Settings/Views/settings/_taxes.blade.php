<div class="form-group">
    <label>{{ trans('fi.default_item_tax_rate') }}: </label>
    {!! Form::select('setting_itemTaxRate', $taxRates, config('fi.itemTaxRate'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_item_tax_2_rate') }}: </label>
    {!! Form::select('setting_itemTax2Rate', $taxRates, config('fi.itemTax2Rate'), ['class' => 'form-control']) !!}
</div>