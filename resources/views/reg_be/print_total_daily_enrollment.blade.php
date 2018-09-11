<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
    .table, .th, .td {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
    }

</style>
<style>
    @page {
                margin: 1cm 1cm;
            }
    body{
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
                margin-top: 4.2cm;
                margin-left: 0cm;
                margin-right: 0cm;
                margin-bottom: 2.5cm;
    }
    
    header {
        position: fixed; 
        top: 0cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 0cm cm 0cm;

    }
    footer {
        position: fixed; 
        bottom: 3cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 0cm cm 0cm;

    }
</style>
<body>
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $x = 700;
            $y = 110;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica");
            $size = 7;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
    <header>
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <!--<div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CHED ENROLLMENT REPORTS</b><br></div>-->    
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br>Enrollment Statistics<br><b>Total Enrollment Statistics</b><br><b>{{$date_start}} , {{$date_end}}</b></div>

    <table  style='margin-top: 135px;' width="100%">

    </table>
    </header>

    <table class='table' width="100%" cellspacing='0' cellpadding='0' border="0" style='margin-top:0px;'>
        <thead>
            <tr>
                <th style="border-top: 1pt dotted black;border-bottom: 1pt dotted black"><?php $counter = 1; ?>#</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Student#</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Last Name</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">First Name</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Level</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Section</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; ?>
                @foreach($students as $student)
            <?php $info = \App\User::where('idno', $student->idno)->first(); ?>    
            <tr>
                <td>{{$count}}</td>
                <td>{{$student->idno}}</td>
                <td>{{$info->lastname}}</td>
                <td>{{$info->firstname}}</td>
                <td>{{$student->level}}</td>
                <td>{{$student->section}}</td>
                </tr>
            <?php $count = $count + 1; ?>
                @endforeach
        </tbody>
    </table>           
    <table width="100%">
        <tr>
            <td><b>Total Number of Students: {{count($students)}}</b></td>
        </tr>
    </table>
</body>