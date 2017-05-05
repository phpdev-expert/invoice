@extends('setup.master')

@section('content')

<section class="content-header">
    <h1>{{ trans('fi.account_setup') }}</h1>
</section>

<section class="content">

    {!! Form::open(['route' => 'setup.postAccount', 'class' => 'form-install']) !!}

    <div class="row">

        <div class="col-md-12">

            <div class="box box-primary">

                <div class="box-body">

                    @include('layouts._alerts')

                    <p>{{ trans('fi.step_about_yourself') }}</p>

                    <div class="row">

                        <div class="col-md-6 form-group">
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('fi.name')]) !!}
                        </div>

                        <div class="col-md-6 form-group">
                            {!! Form::text('company', null, ['class' => 'form-control', 'placeholder' => trans('fi.company')]) !!}
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => trans('fi.address'), 'rows' => 4]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::text('city', null, ['id' => 'city', 'class' => 'form-control', 'placeholder' => trans('fi.city')]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::text('state', null, ['id' => 'state', 'class' => 'form-control', 'placeholder' => trans('fi.state')]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::text('zip', null, ['id' => 'zip', 'class' => 'form-control', 'placeholder' => trans('fi.postal_code')]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::text('country', null, ['id' => 'country', 'class' => 'form-control', 'placeholder' => trans('fi.country')]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3 form-group">
                            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => trans('fi.phone')]) !!}
                        </div>

                        <div class="col-md-3 form-group">
                            {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => trans('fi.mobile')]) !!}
                        </div>

                        <div class="col-md-3 form-group">
                            {!! Form::text('fax', null, ['class' => 'form-control', 'placeholder' => trans('fi.fax')]) !!}
                        </div>

                        <div class="col-md-3 form-group">
                            {!! Form::text('web', null, ['class' => 'form-control', 'placeholder' => trans('fi.web')]) !!}
                        </div>

                    </div>

                    <p>{{ trans('fi.step_create_account') }}</p>

                    <div class="row">

                        <div class="col-md-4 form-group">
                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('fi.email')]) !!}
                        </div>

                        <div class="col-md-4 form-group">
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('fi.password')]) !!}
                        </div>

                        <div class="col-md-4 form-group">
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('fi.password_confirmation')]) !!}
                        </div>

                    </div>

                    <button class="btn btn-primary" type="submit">{{ trans('fi.continue') }}</button>

                </div>

            </div>

        </div>

    </div>

    {!! Form::close() !!}

</section>

@stop