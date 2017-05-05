<script type="text/javascript">
    $(function () {
        $('#allow_login').change(function () {
            toggleClientPasswords();
        });

        function toggleClientPasswords() {
            if ($('#allow_login').val() == 1) {
                $('#div-client-passwords').show();
            }
            else {
                $('#div-client-passwords').hide();
            }
        }

        toggleClientPasswords();
    });
</script>