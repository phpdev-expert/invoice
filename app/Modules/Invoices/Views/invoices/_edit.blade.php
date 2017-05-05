@include('invoices._js_edit')

<section class="content-header">
    <h1 class="pull-left">{{ trans('fi.invoice') }} #{{ $invoice->number }}</h1>

    @if ($invoice->viewed)
        <span style="margin-left: 10px;" class="label label-success">{{ trans('fi.viewed') }}</span>
    @else
        <span style="margin-left: 10px;" class="label label-default">{{ trans('fi.not_viewed') }}</span>
    @endif

    <div class="pull-right">

        <a href="{{ route('invoices.pdf', [$invoice->id]) }}" target="_blank" id="btn-pdf-invoice"
           class="btn btn-default"><i class="fa fa-print"></i> {{ trans('fi.pdf') }}</a>
        @if (config('fi.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-invoice" class="btn btn-default email-invoice"
               data-invoice-id="{{ $invoice->id }}" data-redirect-to="{{ route('invoices.edit', [$invoice->id]) }}"><i
                        class="fa fa-envelope"></i> {{ trans('fi.email') }}</a>
        @endif

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {{ trans('fi.other') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="javascript:void(0)" id="btn-enter-payment" class="enter-payment"
                       data-invoice-id="{{ $invoice->id }}"
                       data-invoice-balance="{{ $invoice->amount->formatted_numeric_balance }}"
                       data-redirect-to="{{ route('invoices.edit', [$invoice->id]) }}"><i
                                class="fa fa-credit-card"></i> {{ trans('fi.enter_payment') }}</a></li>
                <li><a href="javascript:void(0)" id="btn-copy-invoice"><i
                                class="fa fa-copy"></i> {{ trans('fi.copy_invoice') }}</a></li>
                <li><a href="javascript:void(0)" id="btn-recur-invoice"><i
                                class="fa fa-refresh"></i> {{ trans('fi.recur_invoice') }}</a></li>
                <li><a href="{{ route('clientCenter.public.invoice.show', [$invoice->url_key]) }}" target="_blank"><i
                                class="fa fa-globe"></i> {{ trans('fi.public') }}</a></li>
                <li class="divider"></li>
                <li><a href="{{ route('invoices.delete', [$invoice->id]) }}"
                       onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
            </ul>
        </div>

        <div class="btn-group">
            @if ($backPath)
                <a href="{{ URL::to($backPath) }}" class="btn btn-default"><i
                            class="fa fa-backward"></i> {{ trans('fi.back') }}</a>
            @endif
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-invoice"><i
                        class="fa fa-save"></i> {{ trans('fi.save') }}</button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="#" class="btn-save-invoice"
                       data-apply-exchange-rate="1">{{ trans('fi.save_and_apply_exchange_rate') }}</a></li>
            </ul>
        </div>

    </div>

    <div class="clearfix"></div>
</section>

<section class="content">

    <div class="row">

        <div class="col-lg-10">

            <div id="form-status-placeholder"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.summary') }}</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::text('summary', $invoice->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-6" id="col-from">

                    @include('invoices._edit_from')

                </div>

                <div class="col-sm-6" id="col-to">

                    @include('invoices._edit_to')

                </div>

            </div>

            <div class="row">

                <div class="col-sm-12 table-responsive">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.items') }}</h3>

                            <div class="box-tools pull-right">
                                <button class="btn btn-primary btn-sm" id="btn-add-item"><i
                                            class="fa fa-plus"></i> {{ trans('fi.add_item') }}</button>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="item-table" class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">{{ trans('fi.product') }}</th>
                                    <th style="width: 25%;">{{ trans('fi.description') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.qty') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.price') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.tax_1') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.tax_2') }}</th>
                                    <th style="width: 10%; text-align: right; padding-right: 25px;">{{ trans('fi.total') }}</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="new-item" style="display: none;">
                                    <td>
                                        {!! Form::hidden('invoice_id', $invoice->id) !!}
                                        {!! Form::hidden('item_id', '') !!}
                                        {!! Form::text('item_name', null, ['class' => 'form-control']) !!}<br>
                                        <label><input type="checkbox" name="save_item_as_lookup"
                                                      tabindex="999"> {{ trans('fi.save_item_as_lookup') }}</label>
                                    </td>
                                    <td>{!! Form::textarea('item_description', null, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                    <td>{!! Form::text('item_quantity', null, ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::text('item_price', null, ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::select('item_tax_rate_id', $taxRates, config('fi.itemTaxRate'), ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::select('item_tax_rate_2_id', $taxRates, config('fi.itemTax2Rate'), ['class' => 'form-control']) !!}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($invoice->items as $item)
                                    <tr class="item" id="tr-item-{{ $item->id }}">
                                        <td>
                                            {!! Form::hidden('invoice_id', $invoice->id) !!}
                                            {!! Form::hidden('item_id', $item->id) !!}
                                            {!! Form::text('item_name', $item->name, ['class' => 'form-control item-lookup']) !!}
                                        </td>
                                        <td>{!! Form::textarea('item_description', $item->description, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                        <td>{!! Form::text('item_quantity', $item->formatted_quantity, ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('item_price', $item->formatted_numeric_price, ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::select('item_tax_rate_id', $taxRates, $item->tax_rate_id, ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::select('item_tax_rate_2_id', $taxRates, $item->tax_rate_2_id, ['class' => 'form-control']) !!}</td>
                                        <td style="text-align: right; padding-right: 25px;">{{ $item->amount->formatted_subtotal }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-default btn-delete-invoice-item" href="#"
                                               title="{{ trans('fi.delete') }}" data-item-id="{{ $item->id }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.terms_and_conditions') }}</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::textarea('terms', $invoice->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.footer') }}</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::textarea('footer', $invoice->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6">
                    @include('notes._notes', ['notes' => $invoice->notes()->orderBy('created_at', 'desc')->get(), 'module' => 'Invoices', 'objectType' => 'Invoice', 'objectId' => $invoice->id, 'showPrivateCheckbox' => true])
                </div>
            </div>

        </div>

        <div class="col-lg-2">

            <div id="div-totals">
                @include('invoices._edit_totals')
            </div>

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('fi.options') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('fi.invoice') }} #</label>
                        {!! Form::text('number', $invoice->number, ['id' => 'number', 'class' =>
                        'form-control
                        input-sm']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('fi.date') }}</label>
                        {!! Form::text('created_at', $invoice->formatted_created_at, ['id' =>
                        'created_at', 'class' => 'form-control input-sm']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('fi.due_date') }}</label>
                        {!! Form::text('due_at', $invoice->formatted_due_at, ['id' => 'due_at', 'class'
                        => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.currency') }}</label>
                        {!! Form::select('currency_code', $currencies, $invoice->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-control input-sm']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('fi.exchange_rate') }}</label>

                        <div class="input-group">
                            {!! Form::text('exchange_rate', $invoice->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control input-sm']) !!}
                            <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm" id="btn-update-exchange-rate" type="button"
                                                data-toggle="tooltip" data-placement="left"
                                                title="{{ trans('fi.update_exchange_rate') }}"><i class="fa fa-refresh"></i>
                                        </button>
                                    </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('fi.status') }}</label>
                        {!! Form::select('invoice_status_id', $statuses, $invoice->invoice_status_id,
                        ['id' => 'invoice_status_id', 'class' => 'form-control input-sm']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('fi.template') }}</label>
                        {!! Form::select('template', $templates, $invoice->template,
                        ['id' => 'template', 'class' => 'form-control input-sm']) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if ($customFields->count())
        <div class="row">
            <div class="col-md-12">
                @include('custom_fields._custom_fields_unbound', ['object' => $invoice])
            </div>
        </div>
    @endif

</section>