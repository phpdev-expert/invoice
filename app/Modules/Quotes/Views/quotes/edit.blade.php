@extends('layouts.master')

@section('javascript')

    @include('layouts._datepicker')
    @include('layouts._typeahead')
    @include('item_lookups._js_item_lookups')

    <script src="{{ asset('assets/plugins/autosize/jquery-autosize.min.js') }}" type="text/javascript"></script>

@stop

@section('content')

    <div id="div-quote-edit">

        @include('quotes._edit')

    </div>

@stop