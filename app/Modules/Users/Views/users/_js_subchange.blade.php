<script type="text/javascript">

    $(function () {

        $('#modal-lookup-user').modal();

        $('#btn-submit-change-user').click(function () {

            $.post('{{ $updateUserIdRoute }}', {
                user_id: $('#change_user_id').val(),
                id: {{ $id }}


            }).done(function () {
                $('#modal-lookup-user').modal('hide');
                $('#col-from').load('{{ $refreshFromRoute }}', {
                    id: {{ $id }}
                });
            });

        });
    });

</script>