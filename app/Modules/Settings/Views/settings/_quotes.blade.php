<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.default_quote_template') }}: </label>
            {!! Form::select('setting_quoteTemplate', $quoteTemplates, config('fi.quoteTemplate'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.default_group') }}: </label>
            {!! Form::select('setting_quoteGroup', $groups, config('fi.quoteGroup'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>{{ trans('fi.quotes_expire_after') }}: </label>
            {!! Form::text('setting_quotesExpireAfter', config('fi.quotesExpireAfter'), ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

<div class="form-group">
    <label>{{ trans('fi.convert_quote_when_approved') }}: </label>
    {!! Form::select('setting_convertQuoteWhenApproved', $yesNoArray, config('fi.convertQuoteWhenApproved'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.convert_quote_setting') }}: </label>
    {!! Form::select('setting_convertQuoteTerms', $convertQuoteOptions, config('fi.convertQuoteTerms'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_terms') }}: </label>
    {!! Form::textarea('setting_quoteTerms', config('fi.quoteTerms'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_footer') }}: </label>
    {!! Form::textarea('setting_quoteFooter', config('fi.quoteFooter'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.recalculate_quotes') }}: </label><br>
    <button type="button" class="btn btn-default" id="btn-recalculate-quotes"
            data-loading-text="{{ trans('fi.recalculating_wait') }}">{{ trans('fi.recalculate') }}</button>
    <p class="help-block">{{ trans('fi.recalculate_help_text') }}</p>
</div>