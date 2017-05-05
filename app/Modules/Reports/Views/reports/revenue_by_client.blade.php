@extends('layouts.master')

@section('javascript')

    <script type="text/javascript">
        $(function () {
            $('#btn-run-report').click(function () {

                var year = $('#year').val();

                $.post("{{ route('reports.revenueByClient.ajax.validate') }}", {
                    year: year
                }).done(function () {
                    clearErrors();
                    $('#form-validation-placeholder').html('');
                    var output_type = $("input[name=output_type]:checked").val();
                    query_string = "?year=" + year;
                    if (output_type == 'preview') {
                        $('#preview').show();
                        $('#preview-results').attr('src', "{{ route('reports.revenueByClient.html') }}" + query_string);
                    }
                    else if (output_type == 'pdf') {
                        window.open("{{ route('reports.revenueByClient.pdf') }}" + query_string);
                    }
                }).fail(function (response) {
                    showErrors($.parseJSON(response.responseText).errors, '#form-validation-placeholder');
                });
            });
        });
    </script>

@stop

@section('content')

    <section class="content-header">
        <h1 class="pull-left">{{ trans('fi.revenue_by_client') }}</h1>

        <div class="pull-right">
            <button class="btn btn-primary" id="btn-run-report">{{ trans('fi.run_report') }}</button>
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="content">

        <div id="form-validation-placeholder"></div>

        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('fi.options') }}</h3>
                    </div>
                    <div class="box-body">

                        @if ($years)
                            <div class="row">

                                <div class="col-lg-12">
                                    <label>{{ trans('fi.year') }}</label>

                                    <div class="input-group">
                                        {!! Form::select('year', $years, date('Y'), ['id' => 'year', 'class' => 'form-control']) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label>{{ trans('fi.output_type') }}</label><br>
                                        <label class="radio-inline">
                                            <input type="radio" name="output_type" value="preview"
                                                   checked="checked"> {{ trans('fi.preview') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="output_type"
                                                   value="pdf"> {{ trans('fi.pdf') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                        @else
                            <div class="row">
                                <div class="col-lg-12">
                                    <p>{{ trans('fi.report_rev_client_notice') }}</p>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>

        <div class="row" id="preview"
             style="height: 100%; background-color: #e6e6e6; padding: 25px; margin: 0px; display: none;">
            <div class="col-lg-10 col-lg-offset-1" style="background-color: white;">
                <iframe src="about:blank" id="preview-results" frameborder="0" style="width: 100%;" scrolling="no"
                        onload="javascript:resizeIframe(this, 500);"></iframe>
            </div>
        </div>

    </section>

@stop