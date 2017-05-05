<script type="text/javascript">

    function notify(message, type) {
        $.notify({
            message: message
        },{
            type: type
        });
    }

    function showErrors(errors, placeholder) {

        $('.input-group.has-error').removeClass('has-error');
        $(placeholder).html('');
        if (errors == null && placeholder) {
            return;
        }

        $.each(errors, function (id, message) {
            if (id) $('#' + id).parents('.input-group').addClass('has-error');
            if (placeholder) $(placeholder).append('<div class="alert alert-danger">' + message[0] + '</div>');
        });

    }

    function clearErrors() {
        $('.input-group.has-error').removeClass('has-error');
    }

    $(function () {

        $.notifyDefaults({
            placement: {
                from: "bottom"
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.create-quote').click(function () {
            clientName = $(this).data('unique-name');
            $('#modal-placeholder').load('{{ route('quotes.create') }}');
        });

        $('.create-invoice').click(function () {
            clientName = $(this).data('unique-name');
            $('#modal-placeholder').load('{{ route('invoices.create') }}');
        });

        $(document).on('click', '.email-quote', function () {
            $('#modal-placeholder').load('{{ route('quoteMail.create') }}', {
                quote_id: $(this).data('quote-id'),
                redirectTo: $(this).data('redirect-to')
            });
        });

        $(document).on('click', '.email-invoice', function () {
            $('#modal-placeholder').load('{{ route('invoiceMail.create') }}', {
                invoice_id: $(this).data('invoice-id'),
                redirectTo: $(this).data('redirect-to')
            });
        });

        $(document).on('click', '.enter-payment', function () {
            $('#modal-placeholder').load('{{ route('payments.ajax.modalEnterPayment') }}', {
                invoice_id: $(this).data('invoice-id'),
                invoice_balance: $(this).data('invoice-balance'),
                redirectTo: $(this).data('redirect-to'),
            });
        });

    });

    function resizeIframe(obj, minHeight) {
        obj.style.height = '';
        var height = obj.contentWindow.document.body.scrollHeight;

        if (height < minHeight) {
            obj.style.height = minHeight + 'px';
        }
        else {
            obj.style.height = (height + 50) + 'px';
        }
    }
</script>