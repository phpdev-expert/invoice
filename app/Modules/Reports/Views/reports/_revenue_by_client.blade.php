@extends('reports.layouts.master')

@section('content')

<h1 style="text-align: center;">{{ trans('fi.revenue_by_client') }}</h1>

<table class="alternate">
    <thead>
    <tr>
        <th>{{ trans('fi.client') }}</th>
        @foreach ($months as $month)
        <th>{{ $month }}</th>
        @endforeach
        <th>{{ trans('fi.total') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($results as $client=>$amounts)
    <tr>
        <td>{{ $client }}</td>
        @foreach (array_keys($months) as $monthKey)
        <td>{{ $amounts['months'][$monthKey] }}</td>
        @endforeach
        <td>{{ $amounts['total'] }}</td>
        @endforeach
    </tr>
    </tbody>
</table>

@stop