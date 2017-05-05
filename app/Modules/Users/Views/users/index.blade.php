@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.users') }}
        </h1>

        <div class="pull-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
        </div>
        <div class="clearfix"></div>
    </section>

	<section class="content">

		@include('layouts._alerts')

		<div class="row">

			<div class="col-xs-12">

				<div class="box box-primary">

					<div class="box-body no-padding">
						<table class="table table-hover">

							<thead>
								<tr>
									<th>{!! Sortable::link('name', trans('fi.name')) !!}</th>
                                    <th>{!! Sortable::link('email', trans('fi.email')) !!}</th>
									<th>{{ trans('fi.options') }}</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($users as $user)
								<tr>
									<td><a href="{{ route('users.edit', [$user->id]) }}">{{ $user->name }}</a></td>
									<td>{{ $user->email }}</td>
									<td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                {{ trans('fi.options') }} <span class="caret"></span>
                                            </button>
											<ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('users.edit', [$user->id]) }}"><i class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                                                <li><a href="{{ route('users.password.edit', [$user->id]) }}"><i class="fa fa-lock"></i> {{ trans('fi.reset_password') }}</a></li>
                                                <li><a href="{{ route('users.delete', [$user->id]) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
                                            </ul>
                                        </div>
									</td>
								</tr>
								@endforeach
							</tbody>

						</table>
					</div>

				</div>

				<div class="pull-right">
					{!! $users->appends(Input::except('page'))->render() !!}
				</div>

			</div>
			
		</div>

	</section>

@stop