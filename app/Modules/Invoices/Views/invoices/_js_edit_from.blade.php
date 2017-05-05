<script type="text/javascript">
    $(function() {
        $('#btn-change-user').click(function() {
            $('#modal-placeholder').load('{{ route('users.ajax.modalLookup') }}', {
                id: {{ $invoice->id }},
                update_user_id_route: '{{ route('invoiceEdit.updateUser') }}',
                refresh_from_route: '{{ route('invoiceEdit.refreshFrom') }}'
            });
        });
    });
</script>