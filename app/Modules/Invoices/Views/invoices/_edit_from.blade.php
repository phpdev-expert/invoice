@include('invoices._js_edit_from')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('fi.from') }}</h3>

        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" id="btn-change-user"><i
                        class="fa fa-exchange"></i> {{ trans('fi.change') }}</button>
        </div>
    </div>
    <div class="box-body">
        @if ($invoice->user->company) <strong>{{ $invoice->user->company }}</strong><br> @endif
        {{ $invoice->user->name }}<br>
        {!! $invoice->user->formatted_address !!}<br>
        {{ trans('fi.phone') }}: {{ $invoice->user->phone }}<br>
        {{ trans('fi.email') }}: {{ $invoice->user->email }}
    </div>
</div>