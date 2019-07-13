
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset ('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/Ionicons/css/ionicons.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/skins/skin-blue.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('plugins/pace/pace.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
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
            .header{background-color: #003147;
                    padding: 10px;
                    color:#FFF;
            }
            .schoolname{
                font-family:"Old English Text MT";
                font-size: 30pt; 
                color:#DAA520;
            }
            .schoolname2{
                font-family:"Old English Text MT";
                font-size: 30pt;  
                color:#DAA520;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 30px; /* Set the fixed height of the footer here */
                line-height: 30px; /* Vertically center the text there */
                background-color: #ccc;
            }
            .sis{
                text-align: right;

            }
            .sname{
                padding-top: 10px;
            }
            .sis #sisname{
                font-weight: bold; 
            }

            body{
                background-color:white;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid header">    
            <div class="col-md-1 pull-left"><img class="image img-responsive logo" src="{{url('/images','assumption-logo.png')}}"></div>
            <div class="col-md-8 sname"><span class="schoolname">A</span><span class='schoolname2'>ssumption</span> &nbsp;&nbsp; <span class='schoolname'>C</span><span class='schoolname2'>ollege</span></div>
            <div class="col-md-3 sis"><span id="sisname">STUDENT PORTAL</span><br> San Lorenzo Drive, San Lorenzo Village<br> Makati City, 1223</div>
        </div>
        <br>
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <input type="text" id="search" class="form-control" placeholder="Lastname...">

            <div id="studentlist">
                <table class="table table-condensed">

                </table>
            </div>    
        </div>
        <div class="col-md-2"></div>
    </body>

    <script src="{{ asset ('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset ('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset ('dist/js/adminlte.min.js')}}"></script>
    <script src="{{ asset ('bower_components/PACE/pace.min.js')}}"></script>
    <script>
$(document).ajaxStart(function () {
Pace.restart()
})
    </script>
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script>
$(function () {
$('.select2').select2();
});
    </script>
    <script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript">
$(document).ready(function () {
$("#search").keypress(function (e) {
    var theEvent = e || window.event;
    var key = theEvent.keyCode || theEvent.which;
    var array = {};
    array['search'] = $("#search").val();
    array['is_search'] = 1;
    if (key == 13) {
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/getstudentlist",
            data: array,
            success: function (data) {
                $("#studentlist").html(data);
                $("#search").val("");
            }
        });
    }
})
})
    </script>
</html>