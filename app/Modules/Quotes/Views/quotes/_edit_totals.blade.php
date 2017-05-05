<div class="box box-primary">
    <div class="box-body">
        <span class="pull-left"><strong>{{ trans('fi.subtotal') }}</strong></span><span
                class="pull-right">{{ $quote->amount->formatted_subtotal }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.tax') }}</strong></span><span
                class="pull-right">{{ $quote->amount->formatted_tax }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.total') }}</strong></span><span
                class="pull-right">{{ $quote->amount->formatted_total }}</span>

        <div class="clearfix"></div>
    </div>
</div>