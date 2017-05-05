@extends('reports.layouts.master')

@section('content')

<style>
    table {
        font-size: .9em;
    }
</style>

<h1 style="text-align: center;">{{ trans('fi.payments_collected') }}</h1>

<table class="alternate">
    <thead>
    <tr>
        <th style="width: 40%;">{{ trans('fi.client') }}</th>
        <th style="width: 10%;">{{ trans('fi.invoice') }}</th>
        <th style="width: 10%;">{{ trans('fi.payment_method') }}</th>
        <th style="width: 20%;">{{ trans('fi.note') }}</th>
        <th style="width: 10%;">{{ trans('fi.date') }}</th>
        <th class="amount" style="width: 10%;">{{ trans('fi.amount') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($results['payments'] as $payment)
    <tr>
        <td>{{ $payment['client_name'] }}</td>
        <td>{{ $payment['invoice_number'] }}</td>
        <td>{{ $payment['payment_method'] }}</td>
        <td>{{ $payment['note'] }}</td>
        <td>{{ $payment['date'] }}</td>
        <td class="amount">{{ $payment['amount'] }}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="amount"><strong>{{ trans('fi.total') }}</strong></td>
        <td class="amount"><strong>{{ $results['total'] }}</strong></td>
    </tr>
    </tbody>
</table>

@stop