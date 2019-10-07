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
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }
    .table2 {
        border: 1px solid black transparent;
        border-collapse: collapse;
        font: 9pt;
    }
    .underline {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    .top-line {
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        text-align: center;
    }
    .no-border {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>LIST OF SCHOLARS</b><br><b>{{$period}}, {{$school_year}} - {{$school_year + 1}}</b></div>
</div>
<div>
    <?php $control = 1; ?>
    @if(count($scholars)>0)
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>Program Enrolled</th>
            <th>Level</th>
            <th>Scholarship</th>
            <th>Tuition %</th>
            <th>Others %</th>
        </tr>
        @foreach($scholars as $scholar)
        <tr>
            <td>{{$control++}}.</td>
            <td>{{$scholar->idno}}</td>
            <td>{{$scholar->getFullNameAttribute()}}</td>
            <td>{{$scholar->program_code}}</td>
            <td>{{$scholar->level}}</td>
            <td>{{$scholar->discount_description}}</td>
            <td>{{$scholar->tuition_fee}}</td>
            <td>{{$scholar->other_fee}}</td>
        </tr>
        @endforeach
        <tbody>
    </table>
    @endif
</div>