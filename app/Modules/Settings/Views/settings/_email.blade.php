@section('javascript')
    @parent
    <script type="text/javascript">
        $(function () {

            $('#mailPassword').val('');

            updateEmailOptions();

            $('#mailDriver').change(function () {
                updateEmailOptions();
            });

            function updateEmailOptions() {

                $('.email-option').hide();

                mailDriver = $('#mailDriver').val();

                if (mailDriver == 'smtp') {
                    $('.smtp-option').show();
                }
                else if (mailDriver == 'sendmail') {
                    $('.sendmail-option').show();
                }
                else if (mailDriver == 'mail') {
                    $('.phpmail-option').show();
                }
            }

        });
    </script>
@stop

<div class="form-group">
    <label>{{ trans('fi.email_send_method') }}: </label>
    {!! Form::select('setting_mailDriver', $emailSendMethods, config('fi.mailDriver'), ['id' => 'mailDriver', 'class' => 'form-control']) !!}
</div>

<div class="row smtp-option email-option">
    <div class="col-md-3">
        <div class="form-group smtp-option email-option">
            <label>{{ trans('fi.smtp_host_address') }}: </label>
            {!! Form::text('setting_mailHost', config('fi.mailHost'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group smtp-option email-option">
            <label>{{ trans('fi.smtp_host_port') }}: </label>
            {!! Form::text('setting_mailPort', config('fi.mailPort'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group smtp-option email-option">
            <label>{{ trans('fi.smtp_username') }}: </label>
            {!! Form::text('setting_mailUsername', config('fi.mailUsername'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group smtp-option email-option">
            <label>{{ trans('fi.smtp_password') }}: </label>
            {!! Form::password('setting_mailPassword', ['id' => 'mailPassword', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group smtp-option email-option">
            <label>{{ trans('fi.smtp_encryption') }}: </label>
            {!! Form::select('setting_mailEncryption', $emailEncryptions, config('fi.mailEncryption'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group sendmail-option email-option">
    <label>{{ trans('fi.sendmail_path') }}: </label>
    {!! Form::text('setting_mailSendmail', config('fi.mailSendmail'), ['class' => 'form-control']) !!}
</div>

<div class="row smtp-option sendmail-option phpmail-option email-option">
    <div class="col-md-4">
        <div class="form-group smtp-option sendmail-option phpmail-option email-option">
            <label>{{ trans('fi.always_attach_pdf') }}: </label>
            {!! Form::select('setting_attachPdf', $yesNoArray, config('fi.attachPdf'), ['id' => 'attachPdf', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group smtp-option sendmail-option phpmail-option email-option">
            <label>{{ trans('fi.always_cc') }}: </label>
            {!! Form::text('setting_mailDefaultCc', config('fi.mailDefaultCc'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group smtp-option sendmail-option phpmail-option email-option">
            <label>{{ trans('fi.always_bcc') }}: </label>
            {!! Form::text('setting_mailDefaultBcc', config('fi.mailDefaultBcc'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label>{{ trans('fi.default_quote_email_body') }}: </label>
    {!! Form::textarea('setting_quoteEmailBody', config('fi.quoteEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_invoice_email_body') }}: </label>
    {!! Form::textarea('setting_invoiceEmailBody', config('fi.invoiceEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_overdue_invoice_email_body') }}: </label>
    {!! Form::textarea('setting_overdueInvoiceEmailBody', config('fi.overdueInvoiceEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.default_payment_receipt_body') }}: </label>
    {!! Form::textarea('setting_paymentReceiptBody', config('fi.paymentReceiptBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.quote_approved_email_body') }}: </label>
    {!! Form::textarea('setting_quoteApprovedEmailBody', config('fi.quoteApprovedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.quote_rejected_email_body') }}: </label>
    {!! Form::textarea('setting_quoteRejectedEmailBody', config('fi.quoteRejectedEmailBody'), ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<div class="form-group">
    <label>{{ trans('fi.overdue_invoice_reminder_frequency') }}</label>
    {!! Form::text('setting_overdueInvoiceReminderFrequency', config('fi.overdueInvoiceReminderFrequency'), ['class' => 'form-control']) !!}
    <span class="help-block">{{ trans('fi.overdue_invoice_reminder_frequency_help') }}</span>
</div>