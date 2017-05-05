@include('users._js_subchange')

<div class="modal fade" id="modal-lookup-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('fi.change_user') }}</h4>
            </div>
            <div class="modal-body">

                <div id="modal-status-placeholder"></div>

                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('fi.user') }}</label>

                        <div class="col-sm-9">
                            {!! Form::select('change_user_id', $users, null, ['id' => 'change_user_id', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('fi.cancel') }}</button>
                <button type="button" id="btn-submit-change-user" class="btn btn-primary">{{ trans('fi.save') }}
                </button>
            </div>
        </div>
    </div>
</div>