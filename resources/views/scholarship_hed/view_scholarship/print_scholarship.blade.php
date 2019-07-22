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
        border-collapse: collapse;
        font: 9pt;
    }
    strong {
        text-transform: uppercase;
    }


</style>
<div>    
    <div style='float: left; margin-left: 0px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small></div>
</div>
<div>
    <br><br><br><br>
    <br><br><br><br>
    <p style='text-align: right;'>{{date('F n, Y')}}</p>
    <br><br><p style='text-align: center;'><b>SCHOLARSHIP CERTIFICATE</b></p>
    <br><br><br><br>
    <p style="text-align: justify; letter-spacing: 2px">
        This is to certify that <strong>{{$info->lastname}}, {{$info->firstname}} {{$info->middlename}} ({{$status->program_code}})</strong> has been awarded <strong>{{$scholar->discount_description}}</strong> under the
        Assumption College Scholarship Program, which includes
        <strong>@if($scholar->tuition_fee > 0)
            {{$scholar->tuition_fee}}% TUITION FEE
            @if($scholar->misc_fee > 0)
                and {{$scholar->misc_fee}}% MISC. FEE
            @endif
        @else
            @if($scholar->misc_fee > 0)
                {{$scholar->misc_fee}}% MISC. FEE
            @endif
        @endif
        </strong>
        discount for <strong>{{$enrollment_sy->school_year}}-{{$enrollment_sy->school_year+1}} {{$enrollment_sy->period}}</strong>
    </p>
    <br><br><br><br>
    <table width="100%">
        <thead>
            <tr>
                <td>Prepared By:<br><br><br><br></td>
                <td>Approved By:<br><br><br><br></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr>               
                <td><strong>{{strtoupper(Auth::user()->lastname)}}, {{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}}</strong></td>
                <td><strong>Dr. Angela Fabiola Regala<br></strong></td>
                <td></td>
            </tr>
            <tr>
                <td>Scholarship Program Officer</td>
                <td>College Dean</td>
                <td></td>
            </tr>
        </tbody>
    </table>    
</div>