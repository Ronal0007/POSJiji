@extends('layouts.main')
@section('POS')
    active
@stop
@section('content')
    <div class="main-contents">
        <h5 class="ml-1">POS</h5>
        <div class="divider"></div>
        <div class="bg-white">
            <div class="row mt-4">
                <div class="col-md-6 col-xl-5 offset-xl-2">
                    <form method="GET" action="{{route('search.pos')}}">
                        <div class="form-group">
                            <div class="input-group">
                                <input id="search" type="text" name="search" class="form-control" placeholder="search PosId or Kata"/>
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="btn-group" role="group">
                        <div class="dropdown btn-group" role="group">
                            <button class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                                    type="button">Filter POS
                            </button>
                            <div role="menu" class="dropdown-menu">
                                <a role="presentation" href="{{route('pos')}}" class="dropdown-item">All</a>
                                <a role="presentation" href="{{route('filter.pos',\App\POS_STATUS::Active_Site)}}"
                                   class="dropdown-item">Active Site</a>
                                <a role="presentation" href="{{route('filter.pos',\App\POS_STATUS::Idle)}}"
                                   class="dropdown-item">Idle</a>
                                <a role="presentation" href="{{route('filter.pos',\App\POS_STATUS::Maintenance)}}"
                                   class="dropdown-item">Maintenance</a>
                            </div>
                        </div>
                        <a class="btn btn-info" role="button" href="#" style="border: 0" data-toggle="modal"
                           data-target="#add-pos-modal">
                            <i class="fa fa-plus"></i>&nbsp;Add POS</a>
                        <a class="btn btn-danger" role="button" href="#" data-toggle="modal"
                           data-target="#totech-modal">
                            To Tech
                        </a>
                    </div>
                </div>
            </div>
            <h6 class="content-header ml-4">{{$title}} | Total: {{$pos_list->total()}}</h6>
            @if($errors->any())
                <div class="row mb-5">
                    <div class="col-md-4 offset-8">
                        @foreach($errors->all() as $error)
                            <li class="text-danger"><small>{{$error}}</small></li>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($pos_list->total()>0)
                <div class="table-responsive">
                    <table class="table cargo-table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
{{--                            <th>POS ID</th>--}}
                            <th>SNo.</th>
                            <th>IMEI</th>
                            <th>Latest POS#</th>
                            <th>Current POS ID</th>
                            <th>Kata</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pos_list as $pos)
                            <tr>
{{--                                <td class="text-center">{{$pos->id}}</td>--}}
                                <td>{{$pos->sno}}</td>
                                <td>{{$pos->imei}}</td>
                                <td>{{$pos->lastposnumber}}</td>
                                <td>{{$pos->currentposid??"Idle"}}</td>
                                <td>{{$pos->currentkata??"Not Active"}}</td>
                                <td><span class="status {{$pos->class}}">{{$pos->currentstatus}}</span></td>
                                <td>
                                    <div class="options">
                                        @if($pos->currentstatus==\App\POS_STATUS::Idle || $pos->currentstatus==\App\POS_STATUS::returned)
                                            <a href="#" data-toggle="modal" data-target="#edit-pos-modal"
                                               data-id="{{$pos->id}}"
                                               class="option-link edit">
                                                <i class="icon ion-edit"></i>&nbsp;
                                                <span class="link-text"></span>
                                            </a>
                                        @endif
                                        <a href="{{route('view.pos',$pos->id)}}" class="option-link edit">
                                            <i class="fa fa-mail-forward"></i>&nbsp;
                                            <span class="link-text"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <h5 class="text-center text-muted font-italic mb-5">No POS records found</h5>
            @endif
            <div class="pagination-content">
                <nav>
                    {{$pos_list->links()}}
                </nav>
            </div>
        </div>
    </div>
@stop

@section('modal')
    {{--Add pos modal--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="add-pos-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center text-info">New POS</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-7 offset-md-1 offset-lg-1 offset-xl-2 mb-3">
                            <form id="add-pos-form" method="POST" action="{{route('add.pos')}}">
                                {{csrf_field()}}
                                <div class="form-item mb-2">
                                    <label>IMEI:</label>
                                    <input id="imei" class="form-control" name="imei" type="text"
                                           value="{{old('imei')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label>SNO:</label>
                                    <input id="sno" class="form-control" name="sno" type="text" value="{{old('sno')}}">
                                </div>
                                @foreach($tools as $tool)
                                    <div class="form-item mb-2">
                                        <label>Good {{$tool->name}}:</label>
                                        <div class="custom-control custom-switch">
                                            <input class="custom-control-input" type="checkbox" name="{{$tool->name}}"
                                                   id="{{$tool->name}}" {{old($tool->name)?'checked':''}}>
                                            <label class="custom-control-label" for="{{$tool->name}}">No</label>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-info btn-lg btn-block" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Edit POS--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="edit-pos-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center text-info">Edit POS</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-7 offset-md-1 offset-lg-1 offset-xl-2 mb-3">
                            <form id="edit-pos-form" method="POST" action="" style="display: none">
                                <input type="hidden" name="_method" value="PUT">
                                {{csrf_field()}}
                                <div class="form-item mb-2">
                                    <label>IMEI:</label>
                                    <input id="edit_imei" class="form-control" name="imei" type="text">
                                </div>
                                <div class="form-item mb-2">
                                    <label>SNO:</label>
                                    <input id="edit_sno" class="form-control" name="sno" type="text">
                                </div>
                                <div id="pos_tools">

                                </div>
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-info btn-lg btn-block" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                            <div id="loading" style="display: flex;justify-content: center;padding: 30px;">
                                <span role="status" class="spinner-border text-warning"
                                      style="height: 4em;width: 4em;"></span>
                            </div>
                            <div id="message" style="display: flex;justify-content: center;padding: 30px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--To Tech modal--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="totech-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center text-info">POS ZINAZOENDA MATENGENEZO</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-lg-12 col-xl-12 offset-md-1 offset-lg-0 offset-xl-0 mb-3">
                            <form id="totech-pos-form" method="POST" action="{{route('damaged.pos')}}">
                                {{csrf_field()}}
                                <label>POS with At Least One Damaged Tool</label>
                                <div class="to-tech">
                                    @foreach($damaged as $pos)
                                        <div class="form-check"
                                             title="Tools" data-placement="right"
                                             data-toggle="popover" data-trigger="hover"
                                             data-content="@foreach($pos->latestToolsStatus() as $tool=>$value)
                                             {{$tool}}:{{$value?'Fine':'Not Fine'}},
                                                   @endforeach

                                                 ">
                                            <input class="form-check-input" type="checkbox" id="{{$pos->id}}"
                                                   name="ids[]" value="{{$pos->id}}">
                                            <label class="form-check-label" for="{{$pos->id}}">IMEI {{$pos->imei}}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-item mb-2 mt-2">
                                    <label>Description:</label>
                                    <textarea id="desc" name="description" class="form-control" rows="5"></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-lg-8 offset-lg-3">
                                        <button class="btn btn-info btn-lg btn-block" type="submit">Submit</button>
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
        //pop over
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();
        });

        let token = $("meta[name='csrf-token']").attr('content');
        //Switch tools label
        $(document).on('change', ".custom-control.custom-switch input[type='checkbox']", (function () {
            let input = $(this);
            let parent = input.parent();
            // console.log(parent);
            if (input.is(':checked')) {
                parent.find('label').text('Yes');
            } else {
                parent.find('label').text('No');
            }
        }));

        //Submit add-pos-form
        $('#add-pos-form').submit(function () {
            let imei = $('#imei');
            let sno = $('#sno');

            if (imei.val().length < 1) {
                imei.focus();
                return false;
            }
            if (sno.val().length < 1) {
                sno.focus();
                return false;
            }
        });

        //Edit pos modal


        let loadingDiv = $('#loading');
        let messageDiv = $('#message');
        let editForm = $('#edit-pos-form');

        $('#edit-pos-modal').on('shown.bs.modal', function (e) {
            let id = $(e.relatedTarget).data('id');
            let edit_imei = $('#edit_imei');
            let edit_sno = $('#edit_sno');
            let toolsDiv = $('#pos_tools');

            toolsDiv.html('');
            messageDiv.html('');
            loadingDiv.show();
            editForm.hide();
            messageDiv.hide();
            $.ajax({
                type: 'POST',
                url: '{{route('get.pos','')}}/' + id,
                data: {
                    _token: token,
                },
                success: function (result) {
                    // console.log(result);
                    if (result.message == 'success') {
                        editForm.attr('action', '{{route('update.pos','')}}/' + id);
                        edit_imei.val(result.data.imei);
                        edit_sno.val(result.data.sno);

                        $.each(result.data.tools, function (tool, value) {
                            toolsDiv.append(getToolItem(tool, value));
                        });


                        loadingDiv.hide();
                        editForm.show();

                    } else {
                        messageDiv.append("<h5 class='text-muted'>" + result.message + "</h5>");
                        loadingDiv.hide();
                        messageDiv.show();
                    }
                }
            });
        });

        function getToolItem(tool, value) {
            return "<div class='form-item mb-2'>\n" +
                "                                        <label>Good " + tool + ":</label>\n" +
                "                                        <div class='custom-control custom-switch'>\n" +
                "                                            <input class='custom-control-input' type='checkbox' name='" + tool + "'\n" +
                "                                                   id='edit_" + tool + "' " + (value ? 'checked' : '') + ">\n" +
                "                                            <label class='custom-control-label' for='edit_" + tool + "'>" + (value ? 'Yes' : 'No') + "</label>\n" +
                "                                        </div>\n" +
                "                                    </div>";
        }

        //hiding edit pos modal
        $('#edit-pos-modal').on('hidden.bs.modal', function (e) {
            editForm.css('display', 'none');
            loadingDiv.css('display', 'flex');
        });

        //submitting totech pos form
        $('#totech-pos-form').submit(function (e) {
            // e.preventDefault();
            let selected_pos = $(".to-tech input[type='checkbox']");
            let desc = $('#desc');
            let checked = false;
            selected_pos.each(function () {
                if ($(this).is(':checked')) {
                    checked = true;
                }
            });
            if (!checked) {
                alert('Selected At Least One POS');
                return false;
            }
            if (desc.val().trim().length < 1) {
                desc.focus();
                return false;
            }
        });
    </script>
@stop
