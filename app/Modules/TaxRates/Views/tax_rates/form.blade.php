@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        $(function () {
            $('#name').focus();
        });
    </script>

    @if ($editMode == true)
        {!! Form::model($taxRate, ['route' => ['taxRates.update', $taxRate->id]]) !!}
    @else
        {!! Form::open(['route' => 'taxRates.store']) !!}
    @endif

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.tax_rate_form') }}
        </h1>
        <div class="pull-right">
            {!! Form::submit(trans('fi.save'), ['class' => 'btn btn-primary']) !!}
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        @if (isset($taxRateInUse) and $taxRateInUse)
            <div class="alert alert-warning">{{ trans('fi.cannot_edit_tax_rate') }}</div>
        @endif

        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-body">

                        <div class="form-group">
                            <label>{{ trans('fi.tax_rate_name') }}: </label>
                            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <label>{{ trans('fi.tax_rate_percent') }}: </label>
                            @if (isset($taxRateInUse) and $taxRateInUse)
                                {!! Form::text('percent', (($editMode) ? $taxRate->formatted_numeric_percent : null),
                                ['id' => 'percent', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            @else
                                {!! Form::text('percent', (($editMode) ? $taxRate->formatted_numeric_percent : null),
                                ['id' => 'percent', 'class' => 'form-control']) !!}
                            @endif

                        </div>

                        <div class="form-group">
                            <label>{{ trans('fi.compound') }}:</label>
                            @if (isset($taxRateInUse) and $taxRateInUse)
                                {!! Form::select('is_compound', ['0' => trans('fi.no'), '1' => trans('fi.yes')],
                                null, ['class' => 'form-control', 'readonly' => 'readonly', 'disabled' =>
                                'disabled']) !!}
                            @else
                                {!! Form::select('is_compound', ['0' => trans('fi.no'), '1' => trans('fi.yes')],
                                null, ['class' => 'form-control']) !!}
                            @endif

                            <span class="help-block">{{ trans('fi.compound_tax_note') }}</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {!! Form::close() !!}
@stop