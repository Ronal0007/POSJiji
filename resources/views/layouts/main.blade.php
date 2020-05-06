<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/ionicons.min.css')}}">
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,700">--}}
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/timeline.css')}}">
</head>
<body>
<div id="navbar">
    <nav class="navbar navbar-light navbar-expand-md navigation-clean-button">
        <div class="container"><a class="navbar-brand" href="#">Company Name</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                 id="navcol-1">
                <ul class="nav navbar-nav mx-auto">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#"><i
                                class="icon ion-gear-a"></i>&nbsp;Dashboard</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{route('pos')}}"><i
                                class="icon ion-android-phone-portrait"></i>&nbsp;POS</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{route('users')}}"><i
                                class="icon ion-ios-people"></i>&nbsp;Users</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#"><i
                                class="icon ion-pie-graph"></i>&nbsp;Report</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#">
                            <i class="icon ion-settings"></i>&nbsp;Settings</a>
                    </li>
                </ul>
                <span class="navbar-text actions">
                    <a class="btn btn-light action-button" role="button" href="{{route('logout')}}"><i
                            class="icon ion-power"></i>&nbsp; &nbsp;Log out</a>
                </span></div>
        </div>
    </nav>
</div>
<div id="side-menu">
    <a href="{{route('dashboard')}}" class="@yield('dashboard')"><i class="icon ion-gear-a"></i>&nbsp;<span
            class="link-text">Dashboard</span></a>
    <a href="{{route('pos')}}" class="@yield('POS')"><i class="icon ion-android-phone-portrait"></i>&nbsp;<span
            class="link-text">POS</span></a>
    <a href="{{route('users')}}" class="@yield('user')"><i class="icon ion-ios-people"></i>&nbsp;<span
            class="link-text">Users</span></a>
    <a href="{{route('report.pos')}}" class="@yield('report')"><i class="icon ion-pie-graph"></i>&nbsp;<span
            class="link-text">Report</span></a>
    <a href="{{route('setting')}}" class="@yield('setting')"><i class="icon ion-settings"></i>&nbsp;<span class="link-text">Settings</span></a>
</div>
<main id="main">
    <a href="{{route('logout')}}" class="user-btn"><i class="icon ion-power"></i>&nbsp; Log Out</a>
    @yield('content')
</main>
@yield('modal')
@if(Session('msg'))
    <div class="modal fade" role="dialog" tabindex="-1" id="message-modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="text-info text-center">{{Session('msg')}}</p>
                </div>
            </div>
        </div>
    </div>
@endif
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<script>
    //Show message
        @if(Session('msg'))
            let modal = $('#message-modal');
            modal.modal('show');
            setTimeout(function () {
                modal.modal('hide');
            }, 3000);
        @endif
</script>
@yield('script')
</body>
</html>
