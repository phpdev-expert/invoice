@extends('layouts.master')

@section('content')

	<section class="content-header">
		<h1>
			{{ trans('fi.recurring_invoices') }}
		</h1>

	</section>

	<section class="content">

		<div class="row">

			<div class="col-xs-12">

				<div class="box box-primary">

					<div class="box-body no-padding">

						<table class="table table-hover">

							<thead>
								<tr>
									<th>{!! Sortable::link('invoices.number', trans('fi.base_invoice')) !!}</th>
									<th>{!! Sortable::link('clients.name', trans('fi.client_name')) !!}</th>
									<th>{!! Sortable::link('invoices.summary', trans('fi.summary')) !!}</th>
                                    <th>{!! Sortable::link('generate_at', trans('fi.next_date')) !!}</th>
									<th>{!! Sortable::link('stop_at', trans('fi.end_date')) !!}</th>
									<th>{!! Sortable::link('recurring_frequency', trans('fi.every')) !!}</th>
									<th>{{ trans('fi.options') }}</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($recurringInvoices as $recurringInvoice)
								<tr>
									<td><a href="{{ route('invoices.edit', [$recurringInvoice->invoice_id]) }}">{{ $recurringInvoice->invoice->number }}</a></td>
									<td><a href="{{ route('clients.show', [$recurringInvoice->invoice->client_id]) }}">{{ $recurringInvoice->invoice->client->name }}</a></td>
                                    <td>{{ $recurringInvoice->invoice->summary }}</td>
                                    <td>{{ $recurringInvoice->formatted_generate_at }}</td>
									<td>{{ $recurringInvoice->formatted_stop_at }}</td>
									<td>{{ $recurringInvoice->recurring_frequency . ' ' . $frequencies[$recurringInvoice->recurring_period] }}</td>
									<td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                {{ trans('fi.options') }} <span class="caret"></span>
                                            </button>
											<ul class="dropdown-menu dropdown-menu-right">
                                                @if (!$recurringInvoice->formatted_stop_at)
                                                    <li><a href="{{ route('recurring.stop', [$recurringInvoice->id]) }}"><i class="glyphicon glyphicon-ban-circle"></i> {{ trans('fi.stop_recurring') }}</a></li>
                                                @endif
                                                <li><a href="{{ route('recurring.delete', [$recurringInvoice->id]) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                                            </ul>
                                        </div>
									</td>
								</tr>
								@endforeach
							</tbody>

						</table>

					</div>

				</div>

				<div class="pull-right">
					{!! $recurringInvoices->appends(Input::except('page'))->render() !!}
				</div>

			</div>
			
		</div>

	</section>

@stop