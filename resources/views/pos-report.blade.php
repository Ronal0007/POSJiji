@extends('layouts.main')
@section('report')
    active
@stop
@section('content')
    <div class="main-contents">
        <h5 class="ml-1">POS Report</h5>
        <div class="divider"></div>
        <div class="bg-white">
            <div class="row mt-4">
                <div class="col-md-6 col-xl-5 offset-xl-2">
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="float-right">
                        <div class="btn-group" role="group">
                            <a class="btn btn-success" role="button" href="{{route('export.pos')}}" style="border: 0">
                                <i class="fa fa-print"></i>&nbsp;Export Excel</a>
                        </div>
                    </div>
                </div>
            </div>
            <h6 class="content-header ml-4">{{$title}} | Total: {{$pos_list->total()}}</h6>
            @if($pos_list->total()>0)
                <div class="table-responsive">
                    <table class="table cargo-table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Serial No.</th>
                            <th>IMEI</th>
                            <th>Latest POS#</th>
                            <th>Current POS ID</th>
                            <th>User</th>
                            <th>User Phone</th>
                            <th>Time In Active Site</th>
                            <th>Kata</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($num=$pos_list->firstItem())
                        @foreach($pos_list as $pos)
                            <tr>
                                <td class="text-center">{{$num}}</td>
                                <td>{{$pos->sno}}</td>
                                <td>{{$pos->imei}}</td>
                                <td>{{$pos->lastposnumber}}</td>
                                <td>{{$pos->currentposid??"Not Active"}}</td>
                                <td>{{$pos->currentUsername}}</td>
                                <td>{{$pos->currentusernumber}}</td>
                                <td>{{$pos->timeInActiveSite}}</td>
                                <td>{{$pos->currentkata??"Not Active"}}</td>
                                <td>{{$pos->currentstatus}}</td>
                                <td>
                                    <div class="options">
                                        <a href="{{route('view.pos',$pos->id)}}" class="option-link edit">
                                            <i class="fa fa-mail-forward"></i>&nbsp;
                                            <span class="link-text"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @php($num++)
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <h5 colspan="8" class="text-center text-muted font-italic mb-5">No POS records found</h5>
            @endif
            <div class="pagination-content">
                <nav>
                    {{$pos_list->links()}}
                </nav>
            </div>
        </div>
    </div>
@stop
