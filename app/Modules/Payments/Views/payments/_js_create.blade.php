<script type="text/javascript">

    $(function () {

        $('#modal-enter-payment').modal();

        $("#payment_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});

        $('#enter-payment-confirm').click(function () {
            var custom_fields = {};

            $('#payment-custom-fields .custom-form-field').each(function () {
                custom_fields[$(this).data('field-name')] = $(this).val();
            });

            $.post('{{ route('payments.ajax.store') }}', {
                invoice_id: $('#invoice_id').val(),
                amount: $('#payment_amount').val(),
                payment_method_id: $('#payment_method_id').val(),
                paid_at: $('#payment_date').val(),
                note: $('#payment_note').val(),
                custom: JSON.stringify(custom_fields),
                email_payment_receipt: $('#email_payment_receipt').prop('checked')
            }).done(function (response) {
                window.location = '{{ $redirectTo }}';
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