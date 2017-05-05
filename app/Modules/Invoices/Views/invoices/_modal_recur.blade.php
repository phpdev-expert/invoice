@include('layouts._datepicker')
@include('layouts._typeahead')
@include('invoices._js_recur')

<div class="modal fade" id="modal-recur-invoice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('fi.recur_invoice') }}</h4>
            </div>
            <div class="modal-body">

                <div id="modal-status-placeholder"></div>

                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('fi.start_date') }}</label>

                        <div class="col-sm-9">
                            {!! Form::text('recur_generate_at', null, ['id' =>
                            'generate_at', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('fi.every') }}</label>

                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-3">
                                    {!! Form::text('recurring_frequency', '1', ['id' => 'recurring_frequency',
                                    'class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-9">
                                    {!! Form::select('recurring_period', $frequencies, 3, ['id' =>
                                    'recurring_period', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('fi.end_date') }}</label>

                        <div class="col-sm-9">
                            {!! Form::text('stop_at', null, ['id' =>
                            'stop_at', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('fi.cancel') }}</button>
                <button type="button" id="btn-recur-invoice-submit" class="btn btn-primary">{{ trans('fi.submit') }}
                </button>
            </div>
        </div>
    </div>
</div>