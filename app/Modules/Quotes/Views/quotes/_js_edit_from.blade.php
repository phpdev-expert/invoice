<script type="text/javascript">
    $(function() {
        $('#btn-change-user').click(function () {
            $('#modal-placeholder').load('{{ route('users.ajax.modalLookup') }}', {
                id: {{ $quote->id }},
                update_user_id_route: '{{ route('quoteEdit.updateUser') }}',
                refresh_from_route: '{{ route('quoteEdit.refreshFrom') }}'
            });
        });
    });
</script>