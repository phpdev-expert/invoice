@foreach ($errors->all('<div class="alert alert-danger">:message</div>') as $error)
    {!! $error !!}
@endforeach

@if (Session::has('error'))
    <div class="alert alert-danger">{!! Session::get('error') !!}</div>
@endif

@if (Session::has('alert'))
    <div class="alert alert-warning">{!! Session::get('alert') !!}</div>
@endif

@if (Session::has('alertSuccess'))
    <div class="alert alert-success">{!! Session::get('alertSuccess') !!}</div>
@endif

@if (Session::has('alertInfo'))
    <div class="alert alert-info">{!! Session::get('alertInfo') !!}</div>
@endif