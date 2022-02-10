<html lang="">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <title></title>
</head>
<body style="background-color:darkseagreen">
<div class="container col-md-3">
    <div class="h-100 row align-items-center">
        <div class="card">
            <div class="" style="margin: 0 auto"><h1 class="text-success">Login</h1></div>
            <form action="{{route('user.login')}}" method="post">
                @csrf
                <div class="card-body">
                    <div>
                        <label>
                            User name:
                        </label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div>
                        <label>
                            Password:
                        </label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div style="text-align: center">
                    <button type="submit" class="btn btn-success">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
