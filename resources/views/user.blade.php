@extends('layouts.main')
@section('user')
    active
@stop
@section('content')
    <div class="main-contents">
{{--        <h2 class="ml-1">Users</h2>--}}
{{--        <div class="divider"></div>--}}
        <div class="bg-white">
            <h4 class="content-header ml-4">All Users</h4>
            <div>
                <table class="table cargo-table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($num=1)
                    @foreach($users as $user)
                        <tr>
                            <td>{{$num}}.</td>
                            <td>{{$user->fullname}}</td>
                            <td>{{$user->email}}</td>
                        </tr>
                        @php($num++)
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-content">
                <nav>
                    {{--Pagination--}}
                </nav>
            </div>
        </div>
    </div>
@stop

