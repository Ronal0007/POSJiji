@extends('layouts.main')
@section('setting')
    active
@stop
@section('content')
    <div class="main-contents">
        <h2 class="ml-1">Settings</h2>
        <div class="divider"></div>
        <div class="bg-white">
            <div class="col-md-5 mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="ml-4">Tools</h4>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-success btn-sm float-right mr-3" role="button" href="#" style="border: 0"
                           data-toggle="modal"
                           data-target="#add-tool-modal">
                            <i class="fa fa-plus"></i>&nbsp;New Tool</a>
                    </div>
                </div>
                @error('name')
                <small class="text-danger ml-3 mt-3">{{$message}}</small>
                @enderror
                <div style="border:1px solid #eee;border-radius: 20px;box-shadow: 0 0 1px grey;padding: 10px;">
                    <table class="table cargo-table table-borderless table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($num=1)
                        @foreach($tools as $tool)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$tool->name}}</td>
                                <td>
                                    <div class="options float-right">
                                        <a data-toggle="modal" href="#edit-tool-modal"
                                           data-id="{{$tool->id}}" data-name="{{$tool->name}}"
                                           class="option-link edit">
                                            <i class="icon ion-edit"></i>&nbsp;
                                            <span class="link-text"></span>
                                        </a>
                                        <a data-toggle="modal" href="#delete-tool-modal"
                                           data-id="{{$tool->id}}" class="option-link delete">
                                            <i class="icon ion-android-delete"></i>&nbsp;
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
            </div>
            <div class="pagination-content">
                <nav>
                    {{--Pagination--}}
                </nav>
            </div>
        </div>
    </div>
@stop

@section('modal')
    {{--Add tool modal--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="add-tool-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-7 offset-md-1 offset-lg-1 offset-xl-2 mb-3">
                            <form id="add-tool-form" method="POST" action="{{route('add.tool')}}">
                                {{csrf_field()}}
                                <div class="form-item mb-2">
                                    <label>Name:</label>
                                    <input id="name" class="form-control" name="name" type="text"
                                           value="{{old('name')}}">
                                </div>
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-success btn-sm btn-block" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Edit tool modal--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="edit-tool-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-7 offset-md-1 offset-lg-1 offset-xl-2 mb-3">
                            <form id="edit-tool-form" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                {{csrf_field()}}
                                <div class="form-item mb-2">
                                    <label>Name:</label>
                                    <input class="form-control" name="name" type="text"
                                           value="{{old('name')}}">
                                </div>
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-info btn-sm btn-block" type="submit">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Delete tool modal--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="delete-tool-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-7 offset-md-1 offset-lg-1 offset-xl-2 mb-3">
                            <form id="delete-tool-form" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                {{csrf_field()}}
                                <p>Are you sure?</p>
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-danger btn-sm btn-block" type="submit">Delete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>

        //edit
        $('#edit-tool-modal').on('show.bs.modal', function (e) {
            let name = $(e.relatedTarget).data('name');
            let id = $(e.relatedTarget).data('id');
            let url = '{{route('edit.tool','')}}/' + id;
            let form = $('#edit-tool-form');

            form.attr('action', url);
            let input = form.find('input[name="name"]').val(name);
            // console.log(input);
        });

        //delete
        $('#delete-tool-modal').on('show.bs.modal', function (e) {
            let id = $(e.relatedTarget).data('id');
            let url = '{{route('delete.tool','')}}/' + id;
            let form = $('#delete-tool-form');

            form.attr('action', url);
        });
    </script>
@stop

