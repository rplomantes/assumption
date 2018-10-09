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
    body {
        font-size: 10pt;
    }

</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>Subject Related Fee</b></div>
</div>
<div>
    <center style='margin-top: 120px;'>
        <b>SRF Student Balances Report</b><br>
        A.Y.: <u>{{$school_year}} - {{$school_year+1}}, {{$period}}</u><br>
    </center>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='5' style='margin-top: 20px;'>
       <thead>
           <tr>
               <th>#</th>
               <th>ID Number</th>
               <th>Name</th>
               <th>Program</th>
               <th>Level</th>
               <th>Amount to Collect</th>
               <th>Payment Rendered</th>
               <th>Balance</th>
           </tr>
       </thead>
       <tbody>
           @foreach($lists as $list)
           <?php $balance = $list->total_amount - $list->total_payment; 
                $other_info = \App\Status::where('idno', $list->idno)->first();
           ?>
           <tr>
               <td>{{$number++}}</td>
               <td>{{$list->idno}}</td>
               <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td>
               <td>{{$other_info->program_code}}</td>
               <td>{{$other_info->level}}</td>
               <td>{{$list->total_amount}}</td>
               <td>{{$list->total_payment}}</td>
               <td>{{$balance}}</td>
           </tr>
           @endforeach
       </tbody>
   </table>
</div>