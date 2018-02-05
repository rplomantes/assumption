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

</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>Subject Related Fee</b></div>
</div>
<div>
    <center style='margin-top: 135px;'>
        <b>{{$program_name}}</b><br>
        Curriculum Year: <u>{{$curriculum_year}}</u><br>
        {{$level}} - {{$period}}
    </center>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='5' style='margin-top: 20px;'>
       <tr>
           <th>Code</th>
           <th>Subject Description</th>
           <th><div align="center">SRF</div></th>
       </tr>
       <?php $totalsrf = 0; ?>
       @foreach ($programs as $program)
       <?php $totalsrf = $totalsrf + $program->srf; ?>
       <tr>
           <td>{{$program->course_code}}</td>
           <td>{{$program->course_name}}</td>
           <td>{{$program->srf}}</td>
       </tr>
       @endforeach
       <tr>
           <td colspan="2"><b>Total</b></td>
           <td><b>{{number_format($totalsrf,2)}}</b></td>
       </tr>
   </table>
</div>