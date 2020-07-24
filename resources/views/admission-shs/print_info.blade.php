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
    <div style='float: left; margin-left: 150px;'>
        <img src="{{public_path('/images/assumption-logo.png')}}">
    </div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'>
        <span id="schoolname">Assumption College</span> <br>
        <small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br>
        <br><b>STUDENT INFORMATION</b></div>
    <br/><br/>
    <br/><br/>
    <div id="main-content" style="clear:both;">
        <h4>PERSONAL INFORMATION</h4>
        <table>
            <tr>
                <td colspan="2" class="small-label"><small>ID Number</small></td>
                <td colspan="4" class="small-label"><small>Name</small></td>
                <td colspan="2" class="small-label"><small>Admission Status</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{$user->idno}}</td>
                <td colspan="4" class="border-right"><strong>{{$user->lastname}}</strong>, {{$user->firstname}} {{$user->middlename}} {{$user->extensionname}}</td>
                <td colspan="2" class="border-right">@if($status->status == env("FOR_APPROVAL"))
                    For Approval
                    @elseif($status->status == env("PRE_REGISTERED"))
                    Pre-Registered
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="5" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Contact Numbers</small></td>
            </tr>
            <tr>
                <td colspan="5" class="border-right">{{$info->street}} {{$info->barangay}} {{$info->mucipality}} {{$info->province}} {{$info->zip}}</td>
                <td colspan="3" class="border-right">{{$info->tel_no}} {{$info->cel_no}}</td>
            </tr>
            <tr>
                <td colspan="3" class="small-label"><small>Email</small></td>
                <td colspan="2" class="small-label"><small>Birthdate</small></td>
                <td colspan="3" class="small-label"><small>Place of Birth</small></td>
            </tr>
            <tr>
                <td colspan="3" class="border-right">{{$user->email}}</td>
                <td colspan="2" class="border-right">{{$info->date_of_birth}}</td>
                <td colspan="3" class="border-right">{{$info->place_of_birth}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Gender</small></td>
                <td colspan="2" class="small-label"><small>Nationality</small></td>
                <td colspan="2" class="small-label"><small>Religion</small></td>
                <td colspan="2" class="small-label"><small>Citizenship</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{$info->gender}}</td>
                <td colspan="2" class="border-right">{{$info->nationality}}</td>
                <td colspan="2" class="border-right">{{$info->religion}}</td>
                <td colspan="2" class="border-right">
                    @if($user->is_foreign == 0)Filipino
                    @else Foreign
                    @endif
                </td>
            </tr>
        </table>
        <br>
        <label><strong>For Non-Filipinos and Filipinos Born Abroad</strong></label>
        <table>
            <tr>
                <td colspan="4" class="small-label"><small>Immigration Status/Visa Classification</small></td>
                <td colspan="4" class="small-label"><small>Authorized Stay</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->immig_status)}}</td>
                <td colspan="4" class="border-right">{{check($info->auth_stay)}}</td>
            </tr>
            <tr>
                <td colspan="3" class="small-label"><small>Passport Number</small></td>
                <td colspan="2" class="small-label"><small>Expiration Date</small></td>
                <td colspan="3" class="small-label"><small>Place Issued</small></td>
            </tr>
            <tr>
                <td colspan="3" class="border-right">{{check($info->passport)}}</td>
                <td colspan="2" class="border-right">{{check($info->pass_exp_date)}}</td>
                <td colspan="3" class="border-right">{{check($info->passport_place_issued)}}</td>
            </tr>
            <tr>
                <td colspan="3" class="small-label"><small>ACR I-Card No.</small></td>
                <td colspan="2" class="small-label"><small>Date Issued</small></td>
                <td colspan="3" class="small-label"><small>Place Issued</small></td>
            </tr>
            <tr>
                <td colspan="3" class="border-right">{{check($info->acr_no)}}</td>
                <td colspan="2" class="border-right">{{check($info->acr_date_issued)}}</td>
                <td colspan="3" class="border-right">{{check($info->acr_place_issued)}}</td>
            </tr>
        </table>
        <h4>FAMILY BACKGROUND</h4>
        <table>
            <tr>
                <td colspan="4" class="small-label"><small>Father's Name</small></td>
                <td class="small-label"><small>Nationality</small></td>
                <td class="small-label"><small></small></td>
                <td colspan="2" class="small-label"><small>Religion</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->father)}}</td>
                <td class="border-right">{{check($info->f_citizenship)}}</td>
                <td class="border-right">@if($info->f_is_living==1) Living @else Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->f_religion)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Highest Educational Attainment</small></td>
                <td colspan="3" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Occupation/Profession</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->f_education)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_school)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_occupation)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Company Name</small></td>
                <td colspan="3" class="small-label"><small>Company Address</small></td>
                <td colspan="3" class="small-label"><small>Office Tel. No.</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->f_company_name)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_company_address)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_company_number)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Home Telephone Number</small></td>
                <td colspan="2" class="small-label"><small>Cellphone Number</small></td>
                <td colspan="4" class="small-label"><small>Email Address</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->f_phone)}}</td>
                <td colspan="2" class="border-right">{{check($info->f_cell_no)}}</td>
                <td colspan="4" class="border-right">{{check($info->f_email)}}</td>
            </tr>

            <tr>
                <td colspan="2" class="small-label"><small>Member of Any Organization</small></td>
                <td colspan="2" class="small-label"><small>Type of Organization</small></td>
                <td colspan="4" class="small-label"><small>Area of expertise you can share with the school </small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">@if($info->f_any_org==1) Yes @else No @endif</td>
                <td colspan="2" class="border-right">{{check($info->f_type_of_org)}}</td>
                <td colspan="4" class="border-right">{{check($info->f_expertise)}}</td>
            </tr>
            </br>
            <tr>
                <td colspan="4" class="small-label"><small>Mother's Name</small></td>
                <td class="small-label"><small>Nationality</small></td>
                <td class="small-label"><small></small></td>
                <td colspan="2" class="small-label"><small>Religion</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->mother)}}</td>
                <td class="border-right">{{check($info->m_citizenship)}}</td>
                <td class="border-right">@if($info->m_is_living==1) Living @else Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->m_religion)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Highest Educational Attainment</small></td>
                <td colspan="3" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Occupation/Profession</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->m_education)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_school)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_occupation)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Company Name</small></td>
                <td colspan="3" class="small-label"><small>Company Address</small></td>
                <td colspan="3" class="small-label"><small>Office Tel. No.</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->m_company_name)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_company_address)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_company_number)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="small-label"><small>Home Telephone Number</small></td>
                <td colspan="2" class="small-label"><small>Cellphone Number</small></td>
                <td colspan="4" class="small-label"><small>Email Address</small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">{{check($info->m_phone)}}</td>
                <td colspan="2" class="border-right">{{check($info->m_cell_no)}}</td>
                <td colspan="4" class="border-right">{{check($info->m_email)}}</td>
            </tr>

            <tr>
                <td colspan="2" class="small-label"><small>Member of Any Organization</small></td>
                <td colspan="2" class="small-label"><small>Type of Organization</small></td>
                <td colspan="4" class="small-label"><small>Area of expertise you can share with the school </small></td>
            </tr>
            <tr>
                <td colspan="2" class="border-right">@if($info->m_any_org==1) Yes @else No @endif</td>
                <td colspan="2" class="border-right">{{check($info->m_type_of_org)}}</td>
                <td colspan="4" class="border-right">{{check($info->m_expertise)}}</td>
            </tr>
            <tr>
                <td colspan="8" class="small-label"><small>Parent's Civil Status at Present</small></td>
            </tr>
            <tr>
                <td colspan="8" class="border-right">{{check($info->parents_civil_status)}}</td>
            </tr>
        </table>
        <div id="guardian">
            <label><strong>If not living with parents</strong></label>
            <table>
                <tr>
                    <td colspan="5" class="small-label"><small>Guardian's Name</small></td>
                    <td colspan="3" class="small-label"><small>Relation</small></td>
                </tr>
                <tr>
                    <td colspan="5" class="border-right">{{check($info->guardian)}}</td>
                    <td colspan="3" class="border-right">{{check($info->g_relation)}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="small-label"><small>Address</small></td>
                    <td colspan="3" class="small-label"><small>Contact Number</small></td>
                </tr>
                <tr>
                    <td colspan="5" class="border-right">{{check($info->g_address)}}</td>
                    <td colspan="3" class="border-right">{{check($info->g_contact_no)}}</td>
                </tr>
            </table>
        </div>
        <label><strong>Siblings (brothers and sisters)</strong></label>
        <?php $siblings = \App\BedSiblings::where('idno', $user->idno)->get(); ?>
        @if(count($siblings) == 0)
        <table>
            <tr>
                <th colspan="3" align="left"><small>Name</small></th>
                <th colspan="1" align="left"><small>Age</small></th>
                <th colspan="2" align="left"><small>Level/Occupation</small></th>
                <th colspan="2" align="left"><small>School/Work</small></th>
            </tr>
            <tr>
                <td colspan="3" class="normal">&nbsp;</td>
                <td colspan="1" class="normal">&nbsp;</td>
                <td colspan="2" class="normal">&nbsp;</td>
                <td colspan="2" class="normal">&nbsp;</td>
            </tr>
        </table>
        @else
        <table>
            <tr>
                <th colspan="3" align="left"><small>Name</small></th>
                <th colspan="1" align="left"><small>Age</small></th>
                <th colspan="2" align="left"><small>Level/Occupation</small></th>
                <th colspan="2" align="left"><small>School/Work</small></th>
            </tr>
            @foreach($siblings as $sibling)
            <tr>
                <td colspan="3" class="normal">{{check($sibling->sibling)}}</td>
                <td colspan="1" class="normal">{{check($sibling->age)}}</td>
                <td colspan="2" class="normal">{{check($sibling->level)}}</td>
                <td colspan="2" class="normal">{{check($sibling->school)}}</td>
            </tr>
            @endforeach
        </table>   
        @endif
        <br/>
        <div id="alumna">
            <label><strong>Is your mother an Alumna of Assumption College? If yes, year graduated:</strong></label>
            <table>
                <tr>
                    <td colspan="3" class="small-label"><small>Grade School</small></td>
                    <td colspan="3" class="small-label"><small>High School</small></td>
                    <td colspan="3" class="small-label"><small>College</small></td>
                </tr>
                <tr>
                    <td colspan="3" class="border-right">{{check($info->m_alumna_gradeschool_year)}}</td>
                    <td colspan="3" class="border-right">{{check($info->m_alumna_highschool_year)}}</td>
                    <td colspan="3" class="border-right">{{check($info->m_alumna_college_year)}}</td>
                </tr>
            </table>
            <label><strong>Aside from mother, are there other members of your family who are alumnae of Assumption? If yes, please fill out the spaces below</strong></label>
            <?php $alumnis = \App\BedOtherAlumni::where('idno', $user->idno)->get(); ?>
            @if(count($alumnis) == 0)
            <table>
                <tr>
                    <th width="75%" align="left"><small>Name</small></th>
                    <th width="25%" align="left"><small>Relationship</small></th>
                </tr>
                <tr>
                    <td width="75%" class="normal">&nbsp;</td>
                    <td width="25%" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th width="75%" align="left"><small>Name</small></th>
                    <th width="25%" align="left"><small>Relationship</small></th>
                </tr>
                @foreach($alumnis as $alumni)
                <tr>
                    <td width="75%" class="normal">{{check($alumni->alumni)}}</td>
                    <td width="25%" class="normal">{{check($alumni->relationship)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif                             
        </div>
        <h4>ACADEMIC BACKGROUND</h4> 
        <table>
            <tr>
                <td colspan="5" class="small-label"><small>Present School Name</small></td>
                <td colspan="3" class="small-label"><small>Telephone Number</small></td>
            </tr>
            <tr>
                <td colspan="5" class="border-right">{{check($info->present_school)}}</td>
                <td colspan="3" class="border-right">{{check($info->present_tel_no)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Address</small></td>
                <td colspan="2" class="small-label"><small>Principal</small></td>
                <td colspan="2" class="small-label"><small>Guidance Counselor</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->present_school_address)}}</td>
                <td colspan="2" class="border-right">{{check($info->present_principal)}}</td>
                <td colspan="2" class="border-right">{{check($info->present_guidance)}}</td>
            </tr>
        </table>
        <hr>
        <div id="schools">
            <table>
                <tr>
                    <td colspan="3" class="small-label"><small>Preschool</small></td>
                    <td colspan="4" class="small-label"><small>Address</small></td>
                    <td colspan="1" class="small-label"><small>Year</small></td>
                </tr>
                <tr>
                    <td colspan="3" class="border-right">{{check($info->primary)}}</td>
                    <td colspan="4" class="border-right">{{check($info->primary_address)}}</td>
                    <td colspan="1" class="border-right">{{check($info->primary_year)}}</td>
                </tr>
                <tr>
                    <td colspan="3" class="small-label"><small>Elementary</small></td>
                    <td colspan="4" class="small-label"><small>Address</small></td>
                    <td colspan="1" class="small-label"><small>Year</small></td>
                </tr>
                <tr>
                    <td colspan="3" class="border-right">{{check($info->gradeschool)}}</td>
                    <td colspan="4" class="border-right">{{check($info->gradeschool_address)}}</td>
                    <td colspan="1" class="border-right">{{check($info->gradeschool_year)}}</td>
                </tr>
                <tr>
                    <td colspan="3" class="small-label"><small>High School</small></td>
                    <td colspan="4" class="small-label"><small>Address</small></td>
                    <td colspan="1" class="small-label"><small>Year</small></td>
                </tr>
                <tr>
                    <td colspan="3" class="border-right">{{check($info->highschool)}}</td>
                    <td colspan="4" class="border-right">{{check($info->highschool_address)}}</td>
                    <td colspan="1" class="border-right">{{check($info->highschool_year)}}</td>
                </tr>
            </table>
        </div>
        <hr>
        <div id="honors">
            <label><strong>List any honors that the applicant received</strong></label>
            <?php $honors = \App\BedReceivedHonor::where('idno', $user->idno)->get(); ?>
            @if(count($honors) == 0)
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Achievement Award</small></th>
                    <th colspan="2" align="left"><small>Grade / Level</small></th>
                    <th colspan="4" align="left"><small>Name of Event</small></th>
                </tr>
                <tr>
                    <td colspan="3" class="normal">&nbsp;</td>
                    <td colspan="2" class="normal">&nbsp;</td>
                    <td colspan="4" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Achievement Award</small></th>
                    <th colspan="2" align="left"><small>Grade / Level</small></th>
                    <th colspan="4" align="left"><small>Name of Event</small></th>
                </tr>
                @foreach($honors as $honor)
                <tr>
                    <td colspan="3" class="normal">{{check($honor->achievement)}}</td>
                    <td colspan="2" class="normal">{{check($honor->level)}}</td>
                    <td colspan="4" class="normal">{{check($honor->event)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif 
        </div>

        <div id="failure">
            <label><strong>Did the applicant fail in any subject/s in school? If <u>yes</u>, specify the grade level:</strong></label>
            <?php $fails = \App\BedApplicantFail::where('idno', $user->idno)->get(); ?>
            @if(count($fails) == 0)
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Subject</small></th>
                    <th colspan="4" align="left"><small>Grade / Level</small></th>
                </tr>
                <tr>
                    <td colspan="4" class="normal">&nbsp;</td>
                    <td colspan="4" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Subject</small></th>
                    <th colspan="4" align="left"><small>Grade / Level</small></th>
                </tr>
                @foreach($fails as $fail)
                <tr>
                    <td colspan="4" class="normal">{{check($fail->subject)}}</td>
                    <td colspan="4" class="normal">{{check($fail->level)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif 
        </div>
        
        <div id="repeat">
            <label><strong>Did the applicant repeat grade/level? If <u>yes</u>, please provide details below:</strong></label>
            <?php $repeats = \App\BedRepeat::where('idno', $user->idno)->get(); ?>
            @if(count($repeats) == 0)
            <table>
                <tr>
                    <td colspan="8" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr><td class="normal">
                @foreach($repeats as $repeat)
                    {{check($repeat->level) . " "}}  
                @endforeach
                </td>
                <tr>
            </table>   
            @endif 
        </div>
        
        <div id="probation">
            <label><strong>Was the applicant ever placed on probation, suspended, dismissed by any school? If <u>yes</u>, specify offense/s, dates and penalties:</strong></label>
            <?php $probations = \App\BedProbation::where('idno', $user->idno)->get(); ?>
            @if(count($probations) == 0)
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Specify Offense/s</small></th>
                    <th colspan="3" align="left"><small>Date</small></th>
                    <th colspan="3" align="left"><small>Penalty</small></th>
                </tr>
                <tr>
                    <td colspan="3" class="normal">&nbsp;</td>
                    <td colspan="3" class="normal">&nbsp;</td>
                    <td colspan="3" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Specify Offense/s</small></th>
                    <th colspan="3" align="left"><small>Date</small></th>
                    <th colspan="3" align="left"><small>Penalty</small></th>
                </tr>
                @foreach($probations as $probation)
                <tr>
                    <td colspan="3" class="normal">{{check($probation->offense)}}</td>
                    <td colspan="3" class="normal">{{check($probation->date)}}</td>
                    <td colspan="3" class="normal">{{check($probation->penalty)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif 
        </div>
        
        <div id="extra">
            <label><strong>List applicant's extra-curricular activites, including club/organization and specify grade level(e.g. class president, glee club, etc.)</strong></label>
            <?php $extras = \App\BedExtraActivity::where('idno', $user->idno)->get(); ?>
            @if(count($extras) == 0)
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Club / Organization</small></th>
                    <th colspan="3" align="left"><small>Date</small></th>
                </tr>
                <tr>
                    <td colspan="3" class="normal">&nbsp;</td>
                    <td colspan="3" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="3" align="left"><small>Club / Organization</small></th>
                    <th colspan="3" align="left"><small>Date</small></th>
                </tr>
                @foreach($extras as $extra)
                <tr>
                    <td colspan="3" class="normal">{{check($extra->club)}}</td>
                    <td colspan="3" class="normal">{{check($extra->level)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif 
        </div>
        
        <div id="church">
            <label><strong>List applicant's community or Church involvement: (if any)</strong></label>
            <?php $involvements = \App\BedChurchInvolvement::where('idno', $user->idno)->get(); ?>
            @if(count($involvements) == 0)
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Community / Church Involvement</small></th>
                    <th colspan="4" align="left"><small>Year</small></th>
                </tr>
                <tr>
                    <td colspan="4" class="normal">&nbsp;</td>
                    <td colspan="4" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Community / Church Involvement</small></th>
                    <th colspan="4" align="left"><small>Year</small></th>
                </tr>
                @foreach($involvements as $involve)
                <tr>
                    <td colspan="4" class="normal">{{check($involve->involvement)}}</td>
                    <td colspan="4" class="normal">{{check($involve->year)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif 
        </div>
        <h4>MEDICAL HISTORY / PHYSICAL FITNESS</h4>
            <label><strong>Has the applicant undergone any form of therapy? If <u>yes</u>, provide details below and specify the kind of therapy received:</strong></label>
            <?php $therapys = \App\BedUndergoneTherapy::where('idno', $user->idno)->get(); ?>
            @if(count($therapys) == 0)
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Kind of Therapy</small></th>
                    <th colspan="2" align="left"><small>Period of Treatment</small></th>
                </tr>
                <tr>
                    <td colspan="4" class="normal">&nbsp;</td>
                    <td colspan="2" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr>
                    <th colspan="4" align="left"><small>Kind of Therapy</small></th>
                    <th colspan="2" align="left"><small>Period of Treatment</small></th>
                </tr>
                @foreach($therapys as $therapy)
                <tr>
                    <td colspan="4" class="normal">{{check($therapy->therapy)}}</td>
                    <td colspan="2" class="normal">{{check($therapy->treatment)}}</td>
                </tr>
                @endforeach
            </table>   
            @endif
            
            <label><strong>List any health/physical limitations which should be taken into consideration in carrying out school activities:</strong></label>
            <?php $limitations = \App\BedLimitations::where('idno', $user->idno)->get(); ?>
            @if(count($repeats) == 0)
            <table>
                <tr>
                    <td colspan="8" class="normal">&nbsp;</td>
                </tr>
            </table>
            @else
            <table>
                <tr><td class="normal">
                @foreach($limitations as $limit)
                    {{check($limit->limitations) . " "}}  
                @endforeach
                </td>
                <tr>
            </table>   
            @endif 
        <h4>OTHER REQUIREMENTS</h4>
        <?php $ctrrequirements = \App\CtrBedRequirement::where('level', $info->applied_for)->first(); ?>
        <?php $bedrequirements = \App\BedRequirement::where('idno', $user->idno)->first(); ?>
        <table>
            @if($ctrrequirements->psa >= 1)
            <tr>
                <td class="normal">
                        @if($bedrequirements->psa == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                        &nbsp;Original copy and two (2) clear photocopies of Philippine Statistics Authority (PSA) Birth Certificate
                </td>
            </tr>
            @endif
            @if($ctrrequirements->recommendation_form >= 1)
            <tr>
                <td class="normal">
                        @if($bedrequirements->recommedation_form == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Recommendation Forms (duly accomplished by Guidance/ Class Adviser and Principal)
                </td>
            </tr>
            @endif
            @if($ctrrequirements->baptismal_certificate >= 1)
            <tr>
                <td class="normal">
                        @if($bedrequirements->baptismal_certificate == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;One (1) clear photocopy of Baptismal Certificate
                </td>
            </tr>
            @endif
            @if($ctrrequirements->passport_size_photo >= 1)
            <tr>
                <td class="normal">
                       @if($bedrequirements->passport_size_photo == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Four (4) passport size recent colored photos (computer printed & cut-outs are not accepted)
                </td>
            </tr>
            @endif
            @if($ctrrequirements->currentprevious_report_card >= 1)
            <tr>
                <td class="normal">
                       @if($bedrequirements->currentprevious_report_card == 1)<span class="fa fa-check-square">☑</span> @else <span class="fa fa-square">☑</span>@endif
                    &nbsp;Two (2) clear photocopies of PREVIOUS and CURRENT report cards
                </td>
            </tr>
            @endif
            @if($ctrrequirements->narrative_assessment_report >= 1)
            <tr>
                <td class="normal">
                       @if($bedrequirements->narrative_assessment_report == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Two (2) clear photocopies of either Certificate of Attendance or Narrative Assessment Report
                </td>
            </tr>
            @endif
        </table>
        <hr>
        <label><strong>For Foreign Students</strong></label>
        <table>
            <tr>
                <td class="normal">
                    @if($bedrequirements->acr == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Alien Certificate of Registration (ACR)
                </td>
                <td class="normal">
                    @if($bedrequirements->passport == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Photocopy of Passport
                </td>
            </tr>
            <tr>
                <td class="normal">
                    @if($bedrequirements->visa_parent == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Visa/ Working Permit of Parents
                </td>
                <td class="normal">
                     @if($bedrequirements->photocopy_of_dual == 1)<span class="fa fa-check-square"></span> @else <span class="fa fa-square"></span>@endif
                    &nbsp;Photocopy of dual citizenship passports (for dual citizenship)
                </td>
            </tr>
        </table>
    </div>
</body>
