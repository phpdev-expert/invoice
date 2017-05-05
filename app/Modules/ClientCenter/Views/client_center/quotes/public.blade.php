@extends('client_center.layouts.public')

@section('javascript')
    <script type="text/javascript">
        $(function() {
            $('#view-notes').hide();
            $('#btn-notes').click(function() {
                $('#view-doc').toggle();
                $('#view-notes').toggle();
            });
        });
    </script>
@stop

@section('content')

    <section class="content">

        <div class="public-wrapper">

            @include('layouts._alerts')

            <div style="margin-bottom: 15px;">
                <a href="{{ route('clientCenter.public.quote.pdf', [$quote->url_key]) }}" target="_blank"
                   class="btn btn-primary"><i class="fa fa-print"></i> <span>{{ trans('fi.pdf') }}</span>
                </a>
                @if (in_array($quote->status_text, ['draft', 'sent']))
                <a href="{{ route('clientCenter.public.quote.approve', [$quote->url_key]) }}" class="btn btn-success"
                   onclick="return confirm('{{ trans('fi.confirm_approve_quote') }}');">
                    <i class="fa fa-thumbs-up"></i> {{ trans('fi.approve') }}
                </a>
                <a href="{{ route('clientCenter.public.quote.reject', [$quote->url_key]) }}" class="btn btn-danger"
                   onclick="return confirm('{{ trans('fi.confirm_reject_quote') }}');">
                    <i class="fa fa-thumbs-down"></i> {{ trans('fi.reject') }}
                </a>
                @endif
                @if (auth()->check())
                <a href="javascript:void(0)" id="btn-notes" class="btn btn-warning">
                    <i class="fa fa-comments"></i> {{ trans('fi.notes') }}
                </a>
                @endif
            </div>

            <div class="public-doc-wrapper">

                <div id="view-doc">
                    <iframe src="{{ route('clientCenter.public.quote.html', [$urlKey]) }}" frameborder="0"
                            style="width: 100%;" scrolling="no" onload="javascript:resizeIframe(this, 800);"></iframe>
                </div>

                @if (auth()->check())
                <div id="view-notes">
                    @include('notes._notes', ['notes' => $quote->notes()->protect(auth()->user())->orderBy('created_at', 'desc')->get(), 'module' => 'Quotes', 'objectType' => 'Quote', 'objectId' => $quote->id])
                </div>
                @endif

            </div>

        </div>

    </section>

@stop