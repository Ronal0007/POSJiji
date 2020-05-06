<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>POS:Login</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/ionicons.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,700">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
</head>

<body>
<div class="login-clean">
    <form method="post" action="{{route('login')}}">
        <h4 class="text-info text-center">POS Records</h4>
        {{csrf_field()}}
        <div class="illustration"
             style="background-image: url('{{asset('img/pos.svg')}}');height: 150px;background-position: center;background-size: contain;background-repeat: no-repeat;"></div>
        @if($errors->any())
            <small class="text-danger">Invalid email or password</small>
        @endif
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Email" autofocus>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit" role="button">Log In</button>
        </div>
    </form>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
</body>

</html>
