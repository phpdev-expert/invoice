<script type="text/javascript">

    $(function () {
        $('#modal-copy-invoice').modal();

        $('#modal-copy-invoice').on('shown.bs.modal', function () {
            $("#client_name").focus();
        });

        $("#copy_created_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

        var clients = new Bloodhound({
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.num);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: '{{ route('clients.ajax.lookup') }}' + '?query=%QUERY'
        });

        clients.initialize();

        $('#copy_client_name').typeahead(null, {
            minLength: 3,
            source: clients.ttAdapter()
        });

        // Creates the invoice
        $('#btn-copy-invoice-submit').click(function () {
            $.post('{{ route('invoiceCopy.store') }}', {
                invoice_id: {{ $invoice->id }},
                client_name: $('#copy_client_name').val(),
                created_at: $('#copy_created_at').val(),
                group_id: $('#copy_group_id').val(),
                user_id: {{ $user_id }}
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