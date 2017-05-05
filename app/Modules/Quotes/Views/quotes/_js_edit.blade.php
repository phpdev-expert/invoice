<script type="text/javascript">

    $(function () {

        $("#created_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});
        $("#expires_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});
        $('textarea').autosize();

        $('#btn-copy-quote').click(function () {
            $('#modal-placeholder').load('{{ route('quoteCopy.create') }}', {
                quote_id: {{ $quote->id }}
            });
        });

        $('#btn-quote-to-invoice').click(function () {
            $('#modal-placeholder').load('{{ route('quoteToInvoice.create') }}', {
                quote_id: {{ $quote->id }},
                client_id: {{ $quote->client_id }}
            });
        });

        $('#btn-update-exchange-rate').click(function () {
            updateExchangeRate();
        });

        $('#currency_code').change(function () {
            updateExchangeRate();
        });

        function updateExchangeRate() {
            $.post('{{ route('currencies.getExchangeRate') }}', {
                currency_code: $('#currency_code').val()
            }, function (data) {
                $('#exchange_rate').val(data);
            });
        }

        $('.btn-delete-quote-item').click(function() {
            id = $(this).data('item-id');
            $.post('{{ route('quoteItem.delete') }}', {
                id: id
            }).done(function() {
                $('#tr-item-' + id).remove();
                $('#div-totals').load('{{ route('quoteEdit.refreshTotals') }}', {
                    id: {{ $quote->id }}
                });
            });
        });

        $('.btn-save-quote').click(function () {
            var items = [];
            var item_order = 1;
            var custom_fields = {};
            var apply_exchange_rate = $(this).data('apply-exchange-rate');

            $('table tr.item').each(function () {
                var row = {};
                $(this).find('input,select,textarea').each(function () {
                    if ($(this).is(':checkbox')) {
                        if ($(this).is(':checked')) {
                            row[$(this).attr('name')] = 1;
                        }
                        else {
                            row[$(this).attr('name')] = 0;
                        }
                    }
                    else {
                        row[$(this).attr('name')] = $(this).val();
                    }
                });
                row['item_order'] = item_order;
                item_order++;
                items.push(row);
            });

            $('.custom-form-field').each(function () {
                custom_fields[$(this).data('field-name')] = $(this).val();
            });

            $.post('{{ route('quotes.update', [$quote->id]) }}', {
                number: $('#number').val(),
                created_at: $('#created_at').val(),
                expires_at: $('#expires_at').val(),
                quote_status_id: $('#quote_status_id').val(),
                items: JSON.stringify(items),
                terms: $('#terms').val(),
                footer: $('#footer').val(),
                currency_code: $('#currency_code').val(),
                exchange_rate: $('#exchange_rate').val(),
                custom: JSON.stringify(custom_fields),
                apply_exchange_rate: apply_exchange_rate,
                template: $('#template').val(),
                summary: $('#summary').val()
            }).done(function () {
                $('#div-quote-edit').load('{{ route('quoteEdit.refreshEdit', [$quote->id]) }}', function() {
                    notify('{{ trans('fi.quote_successfully_updated') }}', 'success');
                });
            }).fail(function (response) {
                if (response.status == 400) {
                    $.each($.parseJSON(response.responseText).errors, function (id, message) {
                        notify(message, 'danger');
                    });
                } else {
                    notify('{{ trans('fi.unknown_error') }}', 'danger');
                }
            });
        });

        var fixHelper = function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        };

        $("#item-table tbody").sortable({
            helper: fixHelper
        });

    });

</script>