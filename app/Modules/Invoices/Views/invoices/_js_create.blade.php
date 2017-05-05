<script type="text/javascript">

    $(function () {

        $('#create-invoice').modal();

        $('#create-invoice').on('shown.bs.modal', function () {
            $("#create_client_name").focus();
            $('#create_client_name').typeahead('val', clientName);
        });

        $('#create_created_at').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});
        $("#recurring_stop_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

        $('input[name=recurring]:radio').change(function () {
            if ($(this).val() == 1) {
                $('#div-recurring-options').show();
            }
            else {
                $('#div-recurring-options').hide();
            }
        });

        $('#invoice-create-confirm').click(function () {

            $.post('{{ route('invoices.store') }}', {
                user_id: $('#user_id').val(),
                client_name: $('#create_client_name').val(),
                created_at: $('#create_created_at').val(),
                group_id: $('#create_group_id').val(),
                recurring: $("input:radio[name=recurring]:checked").val(),
                recurring_frequency: $('#recurring_frequency').val(),
                recurring_period: $('#recurring_period').val(),
                recurring_stop_at: $('#recurring_stop_at').val()
            }).done(function (response) {
                window.location = '{{ url('invoices') }}' + '/' + response.id + '/edit';
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