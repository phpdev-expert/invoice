<section class="content">

    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">{{ trans('fi.quote_summary') }}</h3>
        </div>
        <div class="box-body">

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $quotesTotalDraft }}</h3>

                            <p>{{ trans('fi.draft_quotes') }}</p>
                        </div>
                        <div class="icon"><i class="ion ion-edit"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=draft">
                            {{ trans('fi.view_draft_quotes') }} <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box bg-olive">
                        <div class="inner">
                            <h3>{{ $quotesTotalSent }}</h3>

                            <p>{{ trans('fi.sent_quotes') }}</p>
                        </div>
                        <div class="icon"><i class="ion ion-share"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=sent">
                            {{ trans('fi.view_sent_quotes') }} <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>{{ $quotesTotalRejected }}</h3>

                            <p>{{ trans('fi.rejected_quotes') }}</p>
                        </div>
                        <div class="icon"><i class="ion ion-thumbsdown"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=rejected">
                            {{ trans('fi.view_rejected_quotes') }} <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $quotesTotalApproved }}</h3>

                            <p>{{ trans('fi.approved_quotes') }}</p>
                        </div>
                        <div class="icon"><i class="ion ion-thumbsup"></i></div>
                        <a class="small-box-footer" href="{{ route('quotes.index') }}?status=approved">
                            {{ trans('fi.view_approved_quotes') }} <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>