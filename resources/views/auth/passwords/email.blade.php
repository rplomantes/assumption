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
        </div><br><br>
<div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header">
                            <div align="center">Reset Password</div>
                                
                        </div>

                        <div class="box-body">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                {{ csrf_field() }}

                                <div class="form-group row">
<!--                                    <label for="idno" class="col-md-4 col-form-label text-md-right">Student ID Number:</label>-->

                                    <div class="col-md-12">
                                        <input id="idno" type="text" class="form-control{{ $errors->has('idno') ? ' is-invalid' : '' }}" name="idno" value="{{ old('idno') }}" required placeholder="User ID">

                                        @if ($errors->has('idno'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('idno') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="col-sm-12 btn btn-primary">
                                            Reset Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
