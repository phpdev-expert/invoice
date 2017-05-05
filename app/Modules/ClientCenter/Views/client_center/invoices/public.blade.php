@extends('client_center.layouts.public')

@section('javascript')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        $(function () {
            $('#view-notes').hide();
            $('#btn-notes').click(function() {
                $('#view-doc').toggle();
                $('#view-notes').toggle();
            });
            $('#btn-direct-payment').click(function () {
                $('#modal-placeholder').load("{{ route('merchant.invoice.modalCc', [$invoice->url_key]) }}", {
                    urlKey: $(this).data('url-key'),
                    merchant: $(this).data('merchant')
                });
            });
        });
    </script>
@stop

@section('content')

    <section class="content">

                <div class="public-wrapper">

                    @include('layouts._alerts')

                    <div style="margin-bottom: 15px;">
                        <a href="{{ route('clientCenter.public.invoice.pdf', [$invoice->url_key]) }}" target="_blank"
                           class="btn btn-primary"><i class="fa fa-print"></i> <span>{{ trans('fi.pdf') }}</span>
                        </a>

                        @if (auth()->check())
                            <a href="javascript:void(0)" id="btn-notes" class="btn btn-warning">
                                <i class="fa fa-comments"></i> {{ trans('fi.notes') }}
                            </a>
                        @endif

                        @if ($invoice->amount->balance > 0)
                            @foreach ($merchants as $key => $merchant)
                                @if ($merchant['enabled'])
                                    @if ($merchant['isRedirect'])
                                        <a href="{{ route('merchant.invoice.pay', [$invoice->url_key, 'merchant' => $key]) }}"
                                           class="btn btn-success"><i class="fa fa-credit-card"></i>
                                            <span>{{ trans('fi.merchant_pay_with') }} {{ $merchant['name'] }}</span></a>
                                    @else
                                        <a href="javascript:void(0)" id="btn-direct-payment"
                                           data-url-key="{{ $invoice->url_key }}" data-merchant="{{ $key }}" class="btn btn-success">
                                            <i class="fa fa-credit-card"></i>
                                            <span>{{ trans('fi.merchant_pay_with') }} {{ $merchant['name'] }}</span></a>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="public-doc-wrapper">

                        <div id="view-doc">
                            <iframe src="{{ route('clientCenter.public.invoice.html', [$urlKey]) }}" frameborder="0"
                                    style="width: 100%;" scrolling="no" onload="javascript:resizeIframe(this, 800);"></iframe>
                        </div>

                        @if (auth()->check())
                            <div id="view-notes">
                                @include('notes._notes', ['notes' => $invoice->notes()->protect(auth()->user())->orderBy('created_at', 'desc')->get(), 'module' => 'Invoices', 'objectType' => 'Invoice', 'objectId' => $invoice->id])
                            </div>
                        @endif

                    </div>

                </div>

    </section>

@stop