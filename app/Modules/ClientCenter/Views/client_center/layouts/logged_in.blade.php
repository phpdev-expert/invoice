@extends('client_center.layouts.master')

@section('sidebar')
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('clientCenter.dashboard') }}">
                <i class="fa fa-dashboard"></i> <span>{{ trans('fi.dashboard') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clientCenter.quotes') }}">
                <i class="fa fa-file-text-o"></i> <span>{{ trans('fi.quotes') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clientCenter.invoices') }}">
                <i class="fa fa-file-text"></i> <span>{{ trans('fi.invoices') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clientCenter.payments') }}">
                <i class="fa fa-credit-card"></i> <span>{{ trans('fi.payments') }}</span>
            </a>
        </li>
    </ul>
@stop

@section('header')
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <li><a href="{{ route('session.logout') }}"><i class="fa fa-power-off"></i></a></li>

        </ul>
    </div>
@stop