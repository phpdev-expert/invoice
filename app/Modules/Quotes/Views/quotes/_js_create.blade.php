<script type="text/javascript">

    $(function () {

        $('#create-quote').modal();

        $('#create-quote').on('shown.bs.modal', function () {
            $("#create_client_name").focus();
            $('#create_client_name').typeahead('val', clientName);
        });

        $("#create_created_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

        $('#quote-create-confirm').click(function () {

            $.post('{{ route('quotes.store') }}', {
                user_id: $('#user_id').val(),
                client_name: $('#create_client_name').val(),
                created_at: $('#create_created_at').val(),
                group_id: $('#create_group_id').val()
            }).done(function (response) {
                window.location = '{{ url('quotes') }}' + '/' + response.id + '/edit';
            }).fail(function (response) {
                if (response.status == 400) {
                    showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
                } else {
                    alert('{{ trans('fi.unknown_error') }}');
                }
            });
        });

    });

</script>