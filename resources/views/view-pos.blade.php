@extends('layouts.main')
@section('POS')
    active
@stop
@section('content')
    <div class="main-contents">
        <h2 class="ml-1">POS Activity</h2>
        <div class="divider"></div>
        <div class="bg-white mb-1">
            <div class="row">
                <div class="col">
                    @if($pos->currentstatus==\App\POS_STATUS::Active_Site || $pos->currentstatus==\App\POS_STATUS::Maintenance)
                        <button class="btn btn-info float-right" type="button" data-target="#return-pos-modal"
                                data-toggle="modal" data-id="{{$pos->id}}">Return POS
                        </button>
                    @endif
                    @if($pos->currentstatus==\App\POS_STATUS::Idle || $pos->currentstatus==\App\POS_STATUS::returned)
                        <button class="btn btn-info float-right mr-1" type="button" data-target="#handover-pos-modal"
                                data-toggle="modal">Hand Over POS
                        </button>
                    @endif
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <p>POS ID:&nbsp;<span>{{$pos->currentposid??"Not Active"}}</span></p>
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-3">
                    <p>SNo:&nbsp;<span>{{$pos->sno}}</span></p>
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-3">
                    <p>IMEI:&nbsp;<span>{{$pos->imei}}</span></p>
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-3">
                    <p>Status:&nbsp;<span>{{$pos->currentstatus}}</span></p>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-sm-6 col-lg-3 col-xl-3 offset-xl-2">
                    <p>Latest POS#: <span>{{$pos->lastposnumber}}</span></p>
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-3">
                    <p>Current User#: <span>{{$pos->currentusernumber}}</span></p>
                </div>
            </div>
            @if($errors->any())
                <div class="row mb-5">
                    <div class="col-md-4 offset-8">
                        <li class="text-danger"><small>All field are required!</small></li>
                    </div>
                </div>
            @endif
            @if(Session('error'))
                <div class="row mb-5">
                    <div class="col-md-4 offset-8">
                        <h6 class="text-danger"><small>{{Session('error')}}</small></h6>
                    </div>
                </div>
            @endif
        </div>
        <div class="timeline">
            {{--pos registration--}}
            <div class="t-container t-left">
                <div class="t-content bg-info">
                    <h5><strong>{{$pos->created_at->format('d M Y H:i')}}</strong></h5>
                    <p>Registered</p>
                    <div class="tool-status mb-3">
                        @foreach($pos->initialToolsStatus->tools as $status=>$value)
                            <span>Good {{$status}}: {{$value?'Yes':'No'}}</span>
                        @endforeach
                    </div>
                    <p>Issued by:{{$pos->creator->fullname}}</p>
                </div>
            </div>
            {{--Other activity--}}
            @foreach($pos->fullEvents()->get() as $event)
                @if($event->event_type=="App\\Handover")
                    <div class="t-container t-right">
                        <div class="t-content bg-info">
                            <h5><strong>{{$event->created_at->format('d M Y H:i')}}</strong></h5>
                            <p>Hand Over</p>
                            <div class="tool-status mb-3">
                                <span>To: {{$event->event->fullname}}</span>
                                <span>User Phone: {{$event->event->user_phone}}</span>
                                <span>POS Phone: {{$event->event->pos_phone}}</span>
                                <span>POS ID: {{$event->event->posid}}</span>
                                <span>Kata: {{$event->event->kata}}</span>
                            </div>
                            <p>Issued by: {{$event->event->issuer->fullname}}</p>
                        </div>
                    </div>
                @elseif($event->event->type_id!=\App\ACTIVITY_TYPE::ToTech)
                    <div class="t-container t-left">
                        <div class="t-content bg-info">
                            <h5><strong>{{$event->created_at->format('d M Y H:i')}}</strong></h5>
                            <p>Returned
                                From {{$event->event->type_id==\App\ACTIVITY_TYPE::FromCustomer?'User':'Technician'}}</p>
                            <div class="tool-status mb-3">
                                @foreach($event->event->toolsstatus->tools as $tool=>$value)
                                    <span>{{$tool}}: {{$value?'Fine':'Not Fine'}}</span>
                                @endforeach
                            </div>
                            <p>Reason:</p>
                            <div class="tool-status mb-3">
                                <span>{{$event->event->description}}</span>
                            </div>
                            <p>Issued by: {{$event->event->issuer->fullname}}</p>
                        </div>
                    </div>
                @else
                    <div class="t-container t-right">
                        <div class="t-content bg-info">
                            <h5><strong>{{$event->created_at->format('d M Y H:i')}}</strong></h5>
                            <p>Maintenance</p>
                            <div class="tool-status mb-3">
                                @foreach($event->event->toolsstatus->tools as $tool=>$value)
                                    <span>{{$tool}}: {{$value?'Fine':'Not Fine'}}</span>
                                @endforeach
                            </div>
                            <p>Descritpion:</p>
                            <div class="tool-status mb-3">
                                <span>{{$event->event->description}}</span>
                            </div>
                            <p>Issued by: {{$event->event->issuer->fullname}}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@stop

@section('modal')
    {{--Hand over pos--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="handover-pos-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center text-info"> Hand Over POS </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 mb-3">
                            @if($pos->hasDamage)
                                <p>Warning <strong class="text-danger">Has Damaged tool</strong></p>
                            @endif
                            <form id="handover-pos-form" method="POST" action="{{route('handover.pos',$pos->id)}}">
                                {{csrf_field()}}
                                <label>Latest POS tools</label>
                                <div class="mb-2"
                                     style="display: flex;flex-wrap: wrap;padding: 20px;border: 1px solid #eee;border-radius: 5px;">
                                    @foreach($pos->latestToolsStatus() as $status=>$value)
                                        <span
                                            style="font-size: 9pt;padding: 2px;border: 1px solid #ccc;border-radius: 10px;margin: 2px;">{{$status}}: 
                                        <span
                                            class="{{$value?'text-success':'text-danger'}}">{{$value?'Fine':'Not Fine'}}</span>
                                    </span>
                                    @endforeach
                                </div>
                                <div class="form-item mb-2">
                                    <label for="fname">First name:</label>
                                    <input id="fname" class="form-control" name="fname" type="text" value="{{old('fname')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="mname">Middle name:</label>
                                    <input id="mname" name="mname" class="form-control" type="text" value="{{old('mname')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="lname">Last name:</label>
                                    <input id="lname" name="lname" class="form-control" type="text" value="{{old('lname')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="user_phone">POS ID:</label>
                                    <input id="pos_id" name="posid" class="form-control" type="text"
                                           placeholder="POS ID" value="{{old('posid')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="user_phone">User phone#:</label>
                                    <input id="user_phone" name="user_phone" class="form-control" type="text"
                                           placeholder="0652963369"
                                           pattern="[0-9]{10}]" title="Ten digits only" maxlength="10" value="{{old('user_phone')}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="pos_phone">Current POS phone#:</label>
                                    <input id="pos_phone" name="pos_phone" class="form-control" type="text" value="{{old('pos_phone')}}"
                                           placeholder="0652963369"
                                           pattern="[0-9]{10}]" title="Ten digits only" maxlength="10">
                                </div>
                                <div class="form-item mb-2">
                                    <label for="kata">Kata:</label>
                                    <input id="kata" name="kata" class="form-control" type="text" value="{{old('kata')}}">
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

    {{--Return POS--}}
    <div class="modal fade" role="dialog" tabindex="-1" id="return-pos-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center text-info">Return POS</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 mb-3">
                            <form id="return-pos-form" action="{{route('return.pos',$pos->id)}}" method="POST">
                                {{csrf_field()}}
                                <div class="form-item mb-2">
                                    <label>POS ID: <span id="pos_id"></span></label></div>
                                <div class="form-item mb-2">
                                    <label>From:</label>
                                    <input class="form-control" type="text" id="from" readonly
                                           value="{{$pos->returnfrom}}">
                                </div>
                                <div class="form-item mb-2">
                                    <label>Description: (optional)</label>
                                    <textarea name="description"
                                              class="form-control text-sm-left" rows="4"
                                              style="font-size: 11pt;"></textarea>
                                </div>
                                @foreach(\App\Tool::all() as $tool)
                                    <div class="form-item mb-2">
                                        <label>Good {{$tool->name}}:</label>
                                        <div class="custom-control custom-switch">
                                            <input class="custom-control-input" type="checkbox" name="{{$tool->name}}"
                                                   id="{{$tool->name}}" {{old($tool->name)?'checked':''}}>
                                            <label class="custom-control-label"
                                                   for="{{$tool->name}}">{{old($tool->name)?'Yes':'No'}}</label>
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
@stop
@section('script')
    <script>
        $('#handover-pos-form').submit(function () {
            let fname = $('#fname');
            let mname = $('#mname');
            let lname = $('#lname');
            let user_phone = $('#user_phone');
            let pos_phone = $('#pos_phone');
            let kata = $('#kata');

            if (fname.val().trim().length < 1) {
                fname.focus();
                return false;
            }
            if (mname.val().trim().length < 1) {
                mname.focus();
                return false;
            }
            if (lname.val().trim().length < 1) {
                lname.focus();
                return false;
            }
            if (user_phone.val().trim().length < 1) {
                user_phone.focus();
                return false;
            }
            if (pos_phone.val().trim().length < 1) {
                pos_phone.focus();
                return false;
            }
            if (kata.val().trim().length < 1) {
                kata.focus();
                return false;
            }
        });

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

        //show return pos modal
        $('#return-pos-modal').on('show.bs.modal', function (e) {
            let id = $(e.relatedTarget).data('id');
            $('#pos_id').text(id);
        });
    </script>
@stop
