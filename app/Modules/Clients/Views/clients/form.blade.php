@extends('layouts.master')

@section('content')

    @if ($editMode)
        {!! Form::model($client, ['route' => ['clients.update', $client->id]]) !!}
    @else
        {!! Form::open(['route' => 'clients.store']) !!}
    @endif

    <section class="content-header">
        <h1 class="pull-left">{{ trans('fi.client_form') }}</h1>

        <div class="pull-right">
            {!! Form::submit(trans('fi.save'), ['class' => 'btn btn-primary']) !!}
        </div>

        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-md-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_general" data-toggle="tab">{{ trans('fi.general') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_general">
                            @include('clients._form')
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>

    {!! Form::close() !!}
@stop