@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.groups') }}
        </h1>
        <div class="pull-right">
            <a href="{{ route('groups.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
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
                                    <th>{!! Sortable::link('prefix', trans('fi.prefix')) !!}</th>
                                    <th>{!! Sortable::link('next_id', trans('fi.next_id')) !!}</th>
                                    <th>{!! Sortable::link('left_pad', trans('fi.left_pad')) !!}</th>
                                    <th>{!! Sortable::link('year_prefix', trans('fi.year_prefix')) !!}</th>
                                    <th>{!! Sortable::link('month_prefix', trans('fi.month_prefix')) !!}</th>
									<th>{{ trans('fi.options') }}</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($groups as $group)
								<tr>
									<td>{{ $group->name }}</td>
									<td>{{ $group->prefix }}</td>
									<td>{{ $group->next_id }}</td>
									<td>{{ $group->left_pad }}</td>
									<td>{{ ($group->prefix_year) ? trans('fi.yes') : trans('fi.no') }}</td>
									<td>{{ ($group->prefix_month) ? trans('fi.yes') : trans('fi.no') }}</td>
									<td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                {{ trans('fi.options') }} <span class="caret"></span>
                                            </button>
											<ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('groups.edit', [$group->id]) }}"><i class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                                                <li><a href="{{ route('groups.delete', [$group->id]) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>
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
					{!! $groups->appends(Input::except('page'))->render() !!}
				</div>

			</div>
			
		</div>

	</section>

@stop