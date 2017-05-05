@extends('layouts.master')

@section('content')

	<section class="content-header">
		<h1 class="pull-left">
			{{ trans('fi.invoices') }}
		</h1>

		<div class="pull-right">
            <div class="btn-group">
                @foreach ($statuses as $liStatus)
                    <a href="{{ route('invoices.index', ['status' => $liStatus]) }}" class="btn btn-default @if ($status == $liStatus) active @endif">{{ trans('fi.' . $liStatus) }}</a>
                @endforeach
            </div>
			<a href="javascript:void(0)" class="btn btn-primary create-invoice"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
		</div>

		<div class="clearfix"></div>
	</section>

	<section class="content">

		@include('layouts._alerts')

		<div class="row">

			<div class="col-xs-12">

				<div class="box box-primary">

					<div class="box-body no-padding">
						@include('invoices._table')
					</div>

				</div>

				<div class="pull-right">
					{!! $invoices->appends(Input::except('page'))->render() !!}
				</div>

			</div>

		</div>

	</section>

@stop