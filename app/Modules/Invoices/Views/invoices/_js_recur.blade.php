<script type="text/javascript">

    $(function () {
        $('#modal-recur-invoice').modal();

        $("#generate_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});
        $("#stop_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

        // Creates the invoice
        $('#btn-recur-invoice-submit').click(function () {
            $.post('{{ route('invoiceRecur.store') }}', {
                invoice_id: {{ $invoice->id }},
                generate_at: $('#generate_at').val(),
                recurring_frequency: $('#recurring_frequency').val(),
                recurring_period: $('#recurring_period').val(),
                stop_at: $('#stop_at').val()
            }).done(function (response) {
                window.location = '{{ route('invoices.edit', [$invoice->id]) }}';
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