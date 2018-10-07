<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - San Lorenzo</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style>
            @font-face {
    font-family: myFirstFont;
    src: url("../../fonts/OLD.ttf");
}
            img {
                display: block;
                max-width:230px;
                max-height:95px;
                width: auto;
                height: auto;
            }
            .header{background-color: #fff;
                    padding: 10px;
            }
            .schoolname{
                font-family:"Old English Text MT";
                font-size: 25pt; 
                color:#003147
            }
            .schoolname2{
                font-family:"Old English Text MT";
                font-size: 25pt;  
                color:#003147
            }
            .footer {
                position:absolute;
                bottom:0;
                width:100%;
                height:30px;   /* Height of the footer */
                background:#fff;
                padding-top: 5px;
            }    
            .sis{
                text-align: right;

            }

            .sis #sisname{
                font-weight: bold; 
            }

            body{background-color:#003147;}
        </style>
    </head>
    <body>
        <div class="container-fluid header">    
            <div class="col-md-1 pull-left"><img class="image img-responsive logo" src="{{url('/images','assumption-logo.png')}}"></div>
            <div class="col-md-8"><span class="schoolname">A</span><span class='schoolname2'>ssumption</span> <span class='schoolname'>C</span><span class='schoolname2'>ollege</span> <br> San Lorenzo Drive, San Lorenzo Village<br> Makati City, 1223</div>
            <div class="col-md-3 sis"><span id="sisname">School Information System</span></div>
        </div>
        <div class="login-box">

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="idno" placeholder="User ID">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->

        </div>
        <!-- /.login-box -->
        <div class="container-fluid footer">

            <!-- To the right -->
            <div class="pull-right hidden-xs">
                In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.

        </div>    
        <!-- jQuery 3 -->
        <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="../../plugins/iCheck/icheck.min.js"></script>
        <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
        </script>
    </body>
</html>
