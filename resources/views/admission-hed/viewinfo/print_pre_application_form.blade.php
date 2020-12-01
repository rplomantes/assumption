<?php

function check($val) {
    if ($val == "" || $val == null) {
        echo "&nbsp;";
    } else {
        echo $val;
    }
}
?>
<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 8pt;
    }
    .normal{
        border: 1px solid black;
    }
    .border-right{
        border-right: 2px solid black;
        border-top:1px solid black;
        padding-left:5px;
    }
    .small-label{
        font-weight: bold;
    }
    table{
        width:100%;
    }
</style>
<body>
    <div style='float: left; margin-left: 170px;'>
        <img src="{{public_path('/images/assumption-logo.png')}}">
    </div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'>
        <span id="schoolname">Assumption College</span> <br>
        <small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br>
        <br><b>PRE-APPLICATION FORM</b></div>
    <br/><br/>
    <br/><br/>
    <br/><br/>
    <br/><br/>
    <br/><br/>
    <div id="main-content" style="clear:both;">
        <table>
            <tr>
                <td class="small-label">Applying For</td>
                @if($adhedinfo->tagged_as == '1')<td class="border-right">Freshman</td>@endif
                @if($adhedinfo->tagged_as == '2')<td class="border-right">Transferee</td>@endif
                @if($adhedinfo->tagged_as == '3')<td class="border-right">Cross Enrollee</td> @endif
            </tr>
            <tr>                            
                <td class="small-label">Last Name</td>
                <td class="border-right">{{$users->lastname}}</td>
                <td class="small-label">First Name</td>
                <td class="border-right">{{$users->firstname}}</td>
                <td class="small-label">Middle Name</td>
                <td class="border-right">{{$users->middlename}}</td>
            </tr>
            <tr>
                <td class="small-label">Address</td>
                <td colspan="5" class="border-right">{{$studentinfos->street}} {{$studentinfos->municipality}} {{$studentinfos->province}}</td>
            </tr>
            <tr>
                <td class="small-label">Contact Numbers</td>
                <td colspan="2" class="border-right">{{$studentinfos->tel_no}}; {{$studentinfos->cell_no}}</td>
                <td class="small-label">Email</td>
                <td colspan="2" class="border-right">{{$user->email}}</td>
            </tr>                        
            <tr>
                <td class="small-label">Birthday</td>
                <td colspan="2" class="border-right">{{$studentinfos->birthdate}}</td>
                <td class="small-label">Birthplace</td>
                <td colspan="2" class="border-right">{{$studentinfos->place_of_birth}}</td>
            </tr>                            
            <tr>    
                <td class="small-label">Citizenship</td>
                <td class="border-right">@if($users->is_foreign == '0') Filipino @elseif($users->is_foreign == '1') Foreigner @elseif($users->is_foreign == '2') Dual Citizen @endif</td>
                <td class="small-label">Specify Citizenship</td>
                <td class="border-right">{{$adhedinfo->specify_citizenhip}}</td>
                <td class="small-label">Civil Status</td>
                <td class="border-right">{{$studentinfos->civil_status}}</td> 
            </tr>  
            <tr>
                <td class="small-label">Last School Attended</td>
                <td class="border-right">{{$studentinfos->last_school_attended}}</td> 
                <td class="small-label">School Address</td>
                <td colspan="3" class="border-right">{{$studentinfos->last_school_address}}</td> 
                </div> 
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td class="small-label">Do you have now, or in the past, a condition/s which require or requires you to see a professional?*</td>
                @if($adhedinfo->see_professional == 10)
                <td></td>
                @else
                <td class="border-right">None</td>
                @endif
            </tr>
            @if($adhedinfo->see_professional == 10)
            <tr>            
                <td class="small-label">Condition</td>
                <td class="border-right">
                    @if($adhedinfo->medical == 1)Medical;
                    @endif
                    @if($adhedinfo->psychological == 1)Psychological;
                    @endif
                    @if($adhedinfo->learning_disability == 1)Learning Disability;
                    @endif
                    @if($adhedinfo->emotional == 1)Emotional;
                    @endif
                    @if($adhedinfo->social == 1)Social;
                    @endif
                    @if($adhedinfo->others == 1)Others 
                    @endif
                </td>
            </tr>    
            <tr>
                <td class="small-label">Please specify condition and type of professional seen</td>
                <td class="border-right">{{$adhedinfo->specify_condition}}</td> 
            </tr>
            @else
            @endif
        </table>
        <br>
        <table>
            <tr>
                <td align="justify">
                    I understand that Assumption College (AC) is registered with the National Privacy Commission office as required under the Data
                    Protection Act 2012. AC will use the information contained in this form for college recruitment purposes and will process my personal
                    data in accordance with the data protection legislation. I hereby voluntarily authorize AC to communicate, either by post mail,
                    telephone, mobile text, email or other means, with me regarding any services, offers and notifications at a later date.
                    In the event that I do now wish to be contacted further, I shall inform the College appropriately.
                </td>
            </tr>
        </table>
</body>
