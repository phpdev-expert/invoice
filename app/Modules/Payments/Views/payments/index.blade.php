@extends('layouts.master')

@section('javascript')
<script type="text/javascript">

	$(function() {

		$('.email-payment-receipt').click(function() {
			$('#modal-placeholder').load("{{ route('paymentMail.create') }}", {
				payment_id: $(this).data('payment-id'),
				redirectTo: $(this).data('redirect-to')
			});
		});

	});

</script>
@stop

@section('content')

	<section class="content-header">
		<h1>
			{{ trans('fi.payments') }}
		</h1>
	</section>

	<section class="content">

		@include('layouts._alerts')

		<div class="row">

			<div class="col-xs-12">

				<div class="box box-primary">

					<div class="box-body no-padding">
						@include('payments._table')
					</div>

				</div>

				<div class="pull-right">
					{!! $payments->appends(Input::except('page'))->render() !!}
				</div>

			</div>
			
		</div>

	</section>

@stop