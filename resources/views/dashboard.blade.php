@extends('layouts.main')
@section('dashboard')
    active
@stop
@section('content')
    <div class="main-contents">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0">
                    <a href="{{route('filter.pos',\App\POS_STATUS::Active_Site)}}" class="dashboard-card bg-info">
                        <h5>ACTIVE</h5><span>{{\App\Pos::filter(\App\POS_STATUS::Active_Site)->count()}}</span><span>Total</span></a>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0">
                    <a href="{{route('filter.pos',\App\POS_STATUS::Idle)}}" class="dashboard-card bg-success ">
                        <h5>IDLE</h5>
                        <span>{{\App\Pos::filter(\App\POS_STATUS::Idle)->count()}}</span><span>Total</span></a>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-4 offset-md-1 offset-lg-0">
                    <a href="{{route('filter.pos',\App\POS_STATUS::Maintenance)}}" class="dashboard-card bg-danger ">
                        <h5>MAINTENANCE</h5>
                        <span>{{\App\Pos::filter(\App\POS_STATUS::Maintenance)->count()}}</span><span>Total</span></a>
                </div>
            </div>
        </div>
    </div>
@stop

