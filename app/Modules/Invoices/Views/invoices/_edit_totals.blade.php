<div class="box box-primary">
    <div class="box-body">
        <span class="pull-left"><strong>{{ trans('fi.subtotal') }}</strong></span><span
                class="pull-right">{{ $invoice->amount->formatted_subtotal }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.tax') }}</strong></span><span
                class="pull-right">{{ $invoice->amount->formatted_tax }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.total') }}</strong></span><span
                class="pull-right">{{ $invoice->amount->formatted_total }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.paid') }}</strong></span><span
                class="pull-right">{{ $invoice->amount->formatted_paid }}</span>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.balance') }}</strong></span><span
                class="pull-right">{{ $invoice->amount->formatted_balance }}</span>

        <div class="clearfix"></div>
    </div>
</div>