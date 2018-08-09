<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    $is_new = \App\BedLevel::where('idno',$idno)->first();
    
    if($names->middlename == NULL){
        $names->middlename = "";
    }else{
        $names->middlename = "(".ucwords(strtolower($names->middlename)).")";
    }
    
    if ($is_new->is_new == 1){
    return strtoupper($names->lastname).", ".strtoupper($names->firstname)." ".strtoupper($names->middlename);
    } else {
    return strtoupper($names->lastname).", ".ucwords(strtolower($names->firstname))." ".$names->middlename;
    }

    
    }
function get_ns($idno){
    $is_new = \App\BedLevel::where('idno',$idno)->first();
    if ($is_new->is_new == 1 && $is_new->level != "Pre-Kinder"){
    return " NS";
    } else {
        return "";
    }
}
$i=1;
?>
<center>
<div><strong>Assumption College</strong></div>
<div>Basic Education Division</div>
<div>School Year 2018-2019</div>
</center><br>

@if($section=="All")
<table width="100%">
    <tr>
        <td>Subject</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Quarter</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Teacher</td>
        <td style="border-bottom: 1px solid" width="40%"></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        <th>
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                
        </center></th>
        <th width="5%" align="center">Sect</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
   
    @if(count($status)>0)
    @foreach($status as $name)
    <tr>
        <td>{{$i++}}.</td>
        @if($value == 'w')
        <td width="1%">{{$name->idno}}</td>
        @endif
        <td width="50%">{{get_name($name->idno)}}{{get_ns($name->idno)}}</td>
        <td align="center">{{$name->section}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 
@else
<table width="100%">
    <tr>
        <td>Subject</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Quarter</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Teacher</td>
        <td style="border-bottom: 1px solid" width="40%"></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        @if($value == 'wo')
        <th>
        @else
        <th colspan="2">
        @endif
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                - {{$section}}
        </center></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr>
        <td>{{$i++}}.</td>
        @if($value == 'w')
        <td width="1%">{{$name->idno}}</td>
        @endif
        <td width="40%">{{get_name($name->idno)}}{{get_ns($name->idno)}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table>    

 
@endif
{{date('M d, Y')}}
