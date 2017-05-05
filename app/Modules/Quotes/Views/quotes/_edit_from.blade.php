@include('quotes._js_edit_from')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('fi.from') }}</h3>

        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" id="btn-change-user"><i
                        class="fa fa-exchange"></i> {{ trans('fi.change') }}</button>
        </div>
    </div>
    <div class="box-body">
        @if ($quote->user->company) <strong>{{ $quote->user->company }}</strong><br> @endif
        {{ $quote->user->name }}<br>
        {!! $quote->user->formatted_address !!}<br>
        {{ trans('fi.phone') }}: {{ $quote->user->phone }}<br>
        {{ trans('fi.email') }}: {{ $quote->user->email }}
    </div>
</div>