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
        <?php $admission_hed = \App\AdmissionHed::where('idno',$user->idno)->first(); ?>
        <br><b>STUDENT INFORMATION</b><br>Applying for SY: {{$admission_hed->applying_for_sy}}-{{$admission_hed->applying_for_sy+1}}</div>
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
                <td colspan="2" class="border-right">@if($status->status == env("REGRET_COLLEGE"))
                    Not Approved
                    @elseif($status->status == env("PRE_REGISTERED_COLLEGE"))
                    Pre-Registered
                    @else
                    Approved
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
                <td colspan="2" class="border-right">{{$info->birthdate}}</td>
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
                    @elseif($user->is_foreign == 1)Foreign
                    @elseif($user->is_foreign == 2)Dual Citizen
                    @endif
                </td>
            </tr>
        </table>
        <br>
        @if($user->is_foreign == 1)
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
                <td colspan="2" class="border-right">{{check($info->passport_exp_date)}}</td>
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
        @endif
        <hr>
        <h4>FAMILY BACKGROUND</h4>
        <h4>PARENT'S INFORMATION</h4>
        <table>
            <tr>
                <td colspan="8" class="small-label"><small>Father's Name</small></td>
                <td colspan="2" class="small-label"><small>Living/Deceased</small></td>
                <td colspan="2" class="small-label"><small>Citizenship</small></td>
            </tr>
            <tr>
                <td colspan="8" class="border-right">{{check($info->father)}}</td>
                <td colspan="2" class="border-right">@if($info->f_is_living==1) Living @elseif($info->f_is_living==2) Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->f_citizenship)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Address</small></td>
                <td colspan="2" class="small-label"><small>Email</small></td>
                <td colspan="3" class="small-label"><small>Tel. No/Mobile No.</small></td>
                <td colspan="3" class="small-label"><small>Highest Education Attainment</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->f_personal_address)}}</td>
                <td colspan="2" class="border-right">{{check($info->f_email)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_personal_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_attainment)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Company Name</small></td>
                <td colspan="2" class="small-label"><small>Occupation</small></td>
                <td colspan="3" class="small-label"><small>Business Phone</small></td>
                <td colspan="3" class="small-label"><small>Business Address</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->f_company_name)}}</td>
                <td colspan="2" class="border-right">{{check($info->f_occupation)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->f_address)}}</td>
            </tr>
            <tr>
                <td colspan="8" class="small-label"><small>Mother's Name</small></td>
                <td colspan="2" class="small-label"><small>Living/Deceased</small></td>
                <td colspan="2" class="small-label"><small>Citizenship</small></td>
            </tr>
            <tr>
                <td colspan="8" class="border-right">{{check($info->mother)}}</td>
                <td colspan="2" class="border-right">@if($info->m_is_living==1) Living @elseif($info->m_is_living==2) Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->m_citizenship)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Address</small></td>
                <td colspan="2" class="small-label"><small>Email</small></td>
                <td colspan="3" class="small-label"><small>Tel. No/Mobile No.</small></td>
                <td colspan="3" class="small-label"><small>Highest Education Attainment</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->m_personal_address)}}</td>
                <td colspan="2" class="border-right">{{check($info->m_email)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_personal_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_attainment)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Company Name</small></td>
                <td colspan="2" class="small-label"><small>Occupation</small></td>
                <td colspan="3" class="small-label"><small>Business Phone</small></td>
                <td colspan="3" class="small-label"><small>Business Address</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->m_company_name)}}</td>
                <td colspan="2" class="border-right">{{check($info->m_occupation)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->m_address)}}</td>
            </tr>
            <tr>
                <td colspan="8" class="small-label"><small>Guardian's Name</small></td>
                <td colspan="2" class="small-label"><small>Living/Deceased</small></td>
                <td colspan="2" class="small-label"><small>Citizenship</small></td>
            </tr>
            <tr>
                <td colspan="8" class="border-right">{{check($info->guardian)}}</td>
                <td colspan="2" class="border-right">@if($info->g_is_living==1) Living @elseif($info->g_is_living==2) Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->g_citizenship)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Address</small></td>
                <td colspan="2" class="small-label"><small>Email</small></td>
                <td colspan="3" class="small-label"><small>Tel. No/Mobile No.</small></td>
                <td colspan="3" class="small-label"><small>Highest Education Attainment</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->g_personal_address)}}</td>
                <td colspan="2" class="border-right">{{check($info->g_email)}}</td>
                <td colspan="3" class="border-right">{{check($info->g_personal_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->g_attainment)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Company Name</small></td>
                <td colspan="2" class="small-label"><small>Occupation</small></td>
                <td colspan="3" class="small-label"><small>Business Phone</small></td>
                <td colspan="3" class="small-label"><small>Business Address</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->g_company_name)}}</td>
                <td colspan="2" class="border-right">{{check($info->g_occupation)}}</td>
                <td colspan="3" class="border-right">{{check($info->g_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->g_address)}}</td>
            </tr>
            <tr><td colspan="12"><h4>FOR MARRIED APPLICANTS</h4></td></tr>

            <tr>
                <td colspan="8" class="small-label"><small>Spouse's Name</small></td>
                <td colspan="2" class="small-label"><small>Living/Deceased</small></td>
                <td colspan="2" class="small-label"><small>Citizenship</small></td>
            </tr>
            <tr>
                <td colspan="8" class="border-right">{{check($info->spouse)}}</td>
                <td colspan="2" class="border-right">@if($info->s_is_living==1) Living @elseif($info->s_is_living==2) Deceased @endif</td>
                <td colspan="2" class="border-right">{{check($info->s_citizenship)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Address</small></td>
                <td colspan="2" class="small-label"><small>Email</small></td>
                <td colspan="3" class="small-label"><small>Tel. No/Mobile No.</small></td>
                <td colspan="3" class="small-label"><small>Highest Education Attainment</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->s_personal_address)}}</td>
                <td colspan="2" class="border-right">{{check($info->s_email)}}</td>
                <td colspan="3" class="border-right">{{check($info->s_personal_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->s_attainment)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Company Name</small></td>
                <td colspan="2" class="small-label"><small>Occupation</small></td>
                <td colspan="3" class="small-label"><small>Business Phone</small></td>
                <td colspan="3" class="small-label"><small>Business Address</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{check($info->s_company_name)}}</td>
                <td colspan="2" class="border-right">{{check($info->s_occupation)}}</td>
                <td colspan="3" class="border-right">{{check($info->s_phone)}}</td>
                <td colspan="3" class="border-right">{{check($info->s_address)}}</td>
            </tr>
        </table>
        <label><strong>Do you have relatives who graduated or study in Assumption?</strong></label>
        <?php $alumnis = \App\StudentInfoAlmuni::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Name</small></th>
                <th width="25%" align="left"><small>Relationship</small></th>
                <th width="35%" align="left"><small>Year Graduated/Study</small></th>
                <th width="25%" align="left"><small>Department(GS,HS,College)</small></th>
                <th width="25%" align="left"><small>Location</small></th>
            </tr>
            @if(count($alumnis) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="35%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($alumnis as $alumni)
            <tr>
                <td width="75%" class="normal">{{check($alumni->name)}}</td>
                <td width="25%" class="normal">{{check($alumni->relationship)}}</td>
                <td width="35%" class="normal">{{check($alumni->year_graduated)}}</td>
                <td width="25%" class="normal">{{check($alumni->department)}}</td>
                <td width="25%" class="normal">{{check($alumni->location)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <label><strong>Siblings(Brothers and Sisters)</strong></label>
        <?php $siblings = \App\StudentInfoSibling::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Name</small></th>
                <th width="25%" align="left"><small>Age</small></th>
                <th width="35%" align="left"><small>Level/Position</small></th>
                <th width="25%" align="left"><small>School/Office</small></th>
            </tr>
            @if(count($siblings) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="35%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($siblings as $alumni)
            <tr>
                <td width="75%" class="normal">{{check($alumni->name)}}</td>
                <td width="25%" class="normal">{{check($alumni->age)}}</td>
                <td width="35%" class="normal">{{check($alumni->level)}}</td>
                <td width="25%" class="normal">{{check($alumni->school)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <?php $pregnant = \App\StudentInfoPregnant::where('idno', $user->idno)->first() ?>
        <table>
            <tr>
                <td colspan="6" class="small-label"><label>Have you ever been pregnant?</label></td>
                <td colspan="6" class="small-label"><label>Are you pregnant now?</label></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">@if($pregnant->ever_pregnant==1) Yes @elseif($pregnant->ever_pregnant==0) No @else &nbsp; @endif</td>
                <td colspan="6" class="border-right">@if($pregnant->pregnant_now==1) Yes @elseif($pregnant->pregnant_now==0) No @else &nbsp; @endif</td>
            </tr>
        </table>
        <label><strong>Do you have children?</strong></label>
        <?php $children = \App\StudentInfoChildren::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Name</small></th>
                <th width="25%" align="left"><small>Age</small></th>
                <th width="35%" align="left"><small>Level</small></th>
                <th width="25%" align="left"><small>School</small></th>
            </tr>
            @if(count($children) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="35%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($children as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->name)}}</td>
                <td width="25%" class="normal">{{check($child->age)}}</td>
                <td width="35%" class="normal">{{check($child->level)}}</td>
                <td width="25%" class="normal">{{check($child->school)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <hr>
        <h4>EDUCATIONAL BACKGROUND</h4>
        <label><strong>Last School Attended</strong></label>
        <table>
            <tr>
                <td colspan="6" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Year</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->last_school_attended}}</td>
                <td colspan="3" class="border-right">{{$info->last_school_address}}</td>
                <td colspan="3" class="border-right">{{$info->last_school_year}}</td>
            </tr>
            <tr>
                <td colspan="4" class="small-label"><small>Principal/College </small></td>
                <td colspan="4" class="small-label"><small>Guidance Counselor</small></td>
                <td colspan="4" class="small-label"><small>School Tel. Number</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{$info->dean}}</td>
                <td colspan="4" class="border-right">{{$info->guidance_counselor}}</td>
                <td colspan="4" class="border-right">{{$info->last_school_number}}</td>
            </tr>
        </table>
        <label><strong>Primary School</strong></label>
        <table>
            <tr>
                <td colspan="6" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Year</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->primary}}</td>
                <td colspan="3" class="border-right">{{$info->primary_address}}</td>
                <td colspan="3" class="border-right">{{$info->primary_year}}</td>
            </tr>
            <tr>
                <td><label><strong>Grade School</strong></label></td>
            </tr>
            <tr>
                <td colspan="6" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Year</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->gradeschool}}</td>
                <td colspan="3" class="border-right">{{$info->gradeschool_address}}</td>
                <td colspan="3" class="border-right">{{$info->gradeschool_year}}</td>
            </tr>
            <tr>
                <td><label><strong>Junior High School</strong></label></td>
            </tr>
            <tr>
                <td colspan="6" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Year</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->highschool}}</td>
                <td colspan="3" class="border-right">{{$info->highschool_address}}</td>
                <td colspan="3" class="border-right">{{$info->highschool_year}}</td>
            </tr>
            <tr>
                <td><label><strong>Senior High School</strong></label></td>
            </tr>
            <tr>
                <td colspan="6" class="small-label"><small>School</small></td>
                <td colspan="3" class="small-label"><small>Address</small></td>
                <td colspan="3" class="small-label"><small>Year</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->senior_highschool}}</td>
                <td colspan="3" class="border-right">{{$info->senior_highschool_address}}</td>
                <td colspan="3" class="border-right">{{$info->senior_highschool_year}}</td>
            </tr>
        </table>
        <h4>FOR TRANSFEREES:</h4>
        <label><strong>Have you ever applied at the Assumption College in the past?</strong></label>
        <table>
            <tr>
                <td colspan="6" class="small-label"><small>If yes, Year and Course applied</small></td>
                <td colspan="6" class="small-label"><small>Reason for leaving</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$info->applied_year_course}}</td>
                <td colspan="6" class="border-right">{{$info->applied_leaving}}</td>
            </tr>
        </table>
        <label><strong>Colleges/Universities attended</strong></label>
        <?php $attendeds = \App\StudentInfoAttendedCollege::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Name of School</small></th>
                <th width="25%" align="left"><small>Address</small></th>
                <th width="35%" align="left"><small>Course</small></th>
                <th width="25%" align="left"><small>Year Attended</small></th>
            </tr>
            @if(count($attendeds) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="35%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($attendeds as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->college)}}</td>
                <td width="25%" class="normal">{{check($child->address)}}</td>
                <td width="35%" class="normal">{{check($child->course)}}</td>
                <td width="25%" class="normal">{{check($child->school_year)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <h4>ACADEMIC HONORS AND DISTINCTIONS</h4>
        <label><strong>Please list all academic honors, distinctions, awards earned.</strong></label>
        <?php $honors = \App\StudentInfoHonor::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Honor/Award</small></th>
                <th width="25%" align="left"><small>Year Level</small></th>
                <th width="35%" align="left"><small>Event</small></th>

            </tr>
            @if(count($honors) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="35%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($honors as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->honor)}}</td>
                <td width="25%" class="normal">{{check($child->level)}}</td>
                <td width="35%" class="normal">{{check($child->event)}}</td>
            </tr>
            @endforeach 
            @endif
            <tr>
                <td colspan="12" class="small-label">Are you a candidate for Class Valedictorian, Salutatorian, or Honorable Mentions? If Yes, please specify.</td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$info->are_you_candidate}}</td>
            </tr>
        </table>
        <h4>DISCONTINUANCE OF STUDY</h4>
        <label><strong>Did you ever have to stop studying?</strong></label>
        <?php $discontinuance = \App\StudentInfoDiscontinuance::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="25%" align="left"><small>School Year</small></th>
                <th width="75%" align="left"><small>Reason</small></th>

            </tr>
            @if(count($discontinuance) == 0)
            <tr>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="75%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($discontinuance as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->school_year)}}</td>
                <td width="25%" class="normal">{{check($child->reason)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <h4>ACADEMIC PROBLEMS</h4>
        <label><strong>Did you fail in any subject(s) in hight school/college?</strong></label>
        <?php $fails = \App\StudentInfoFailSubject::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Subject</small></th>
                <th width="25%" align="left"><small>Grading Period</small></th>
                <th width="25%" align="left"><small>Level</small></th>
                <th width="75%" align="left"><small>Reason</small></th>

            </tr>
            @if(count($fails) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="75%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($fails as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->subject)}}</td>
                <td width="25%" class="normal">{{check($child->period)}}</td>
                <td width="25%" class="normal">{{check($child->level)}}</td>
                <td width="75%" class="normal">{{check($child->reason)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <label><strong>Did you ever have to repeat a year in high school?</strong></label>
        <?php $repeats = \App\StudentInfoRepeat::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="25%" align="left"><small>Level</small></th>
                <th width="75%" align="left"><small>Subject</small></th>
                <th width="75%" align="left"><small>Reason</small></th>

            </tr>
            @if(count($repeats) == 0)
            <tr>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="75%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($repeats as $child)
            <tr>
                <td width="25%" class="normal">{{check($child->level)}}</td>
                <td width="75%" class="normal">{{check($child->subject)}}</td>
                <td width="75%" class="normal">{{check($child->reason)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <h4>DISCIPLINARY RECORD</h4>
        <label><strong>Were you ever placed on probation, suspension, or expelled from school?</strong></label>
        <?php $suspensions = \App\StudentInfoSuspension::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Offense</small></th>
                <th width="25%" align="left"><small>Penalty</small></th>
                <th width="25%" align="left"><small>Period</small></th>

            </tr>
            @if(count($suspensions) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($suspensions as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->offense)}}</td>
                <td width="25%" class="normal">{{check($child->penalty)}}</td>
                <td width="25%" class="normal">{{check($child->period)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <label><strong>Were you ever expelled or refused enrollment from your high school, college or university?</strong></label>
        <table>
            <tr>
                <td colspan="12" class="small-label"><small>If yes, please specify.</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$info->is_expelled_reason}}</td>
            </tr>
        </table>
        <h4>ACTIVITIES</h4>
        <label><strong>List all activities, jobs, and interest outside of class. Transferees must include college activities. Please include position held an other special responsibilities.</strong></label>
        <?php $activities = \App\StudentInfoActivity::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>Activity or Organization</small></th>
                <th width="25%" align="left"><small>Year Level</small></th>
                <th width="25%" align="left"><small>Number of hours involved per day/week/month </small></th>

            </tr>
            @if(count($activities) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($activities as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->activity)}}</td>
                <td width="25%" class="normal">{{check($child->level)}}</td>
                <td width="25%" class="normal">{{check($child->hours)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <label><strong>Have you ever been elected/appointed as class officer?</strong></label>
        <table>
            <tr>
                <td colspan="12" class="small-label"><small>If yes, please specify.</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$info->is_officer}}</td>
            </tr>
        </table>
        <label><strong>Do you currently(or in the past) have a modelling contract?</strong></label>
        <table>
            <tr>
                <td colspan="12" class="small-label"><small>If yes, please specify.</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$info->is_modelling}}</td>
            </tr>
        </table>
        <h4>COLLEGE APPLICATION</h4>
        <label><strong>In order of preference, please list colleges or universities you have applied or intend to apply to.</strong></label>
        <?php $intends = \App\StudentInfoIntend::where('idno', $user->idno)->get(); ?>
        <table>
            <tr>
                <th width="75%" align="left"><small>College/University </small></th>
                <th width="25%" align="left"><small>Course</small></th>
                <th width="25%" align="left"><small>Have you taken entrance test? </small></th>

            </tr>
            @if(count($intends) == 0)
            <tr>
                <td width="75%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
                <td width="25%" class="normal">&nbsp;</td>
            </tr>
            @else>
            @foreach($intends as $child)
            <tr>
                <td width="75%" class="normal">{{check($child->college)}}</td>
                <td width="25%" class="normal">{{check($child->course)}}</td>
                <td width="25%" class="normal">{{check($child->is_taken)}}</td>
            </tr>
            @endforeach 
            @endif
        </table>
        <label><strong>Please rank(1 being the highest) top 5 factors which helped in choosing Assumption College.</strong></label>
        <?php $school_ranks = \App\StudentInfoSchoolRank::where('idno', $user->idno)->first(); ?>
        <?php $array_ranks = array('academic_excellence', 'womens_college', 'values_formation', 'flyer', 'family', 'security', 'college_fair', 'hs_counselor', 'location', 'ac_graduate', 'friend', 'assumption_career', 'parents_choice', 'courses', 'prestige', 'ac_student', 'newspaper'); ?>
        <?php $rank_name = array('Academic Excellence', "Women's College", 'Values Formation', 'Flyer', 'Family', 'Security', 'College Fair', 'HS Counselor', 'Location', 'AC Graduate', 'Friend', 'Assumption Career', "Parent's Choice", 'Courses', 'Prestige', 'AC Student', 'Newspaper Ad'); ?>
        <table>
            <?php $ids=0; ?>
            @foreach($array_ranks as $key)
            @if($school_ranks->$key > 0 )
            <tr>
                <th width="10%" align="left"><small>{{$rank_name[$ids]}}</small></th>
                <th width="10%" align="left"><small>{{$school_ranks->$key}}</small></th>

            </tr>
            @endif
            <?php $ids = $ids+1; ?>
            @endforeach
        </table>
        <hr>
        <label><strong>Please rank in numerical order the top 3 course preferences offered by Assumption College</strong></label>
        <?php $course_rank = \App\StudentInfoCoursesRank::where('idno', $user->idno)->first(); ?>
        <table>
            <tr>
                <td colspan="12" class="small-label"><small>Rank 1</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$course_rank->rank_1}}</td>
            </tr>
            <tr>
                <td colspan="12" class="small-label"><small>Rank 2</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$course_rank->rank_2}}</td>
            </tr>
            <tr>
                <td colspan="12" class="small-label"><small>Rank 3</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$course_rank->rank_3}}</td>
            </tr>
            <tr>
                <td colspan="12" class="small-label"><small>Why did you select your most preferred course?</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$course_rank->why_most_preferred}}</td>
            </tr>
            <tr>
                <td colspan="12" class="small-label"><small>Who decided on your course/study in Assumption?</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$course_rank->who_decided}}</td>
            </tr>
        </table>
        <h4>EMERGENCY INFORMATION</h4>
        <label><strong>Please provide the name of a contact person other than your parents whom the Admissions Office can call.</strong></label>
        <?php $emergency = \App\StudentInfoEmergency::where('idno', $user->idno)->first(); ?>
        <table>
            <tr>
                <td colspan="12" class="small-label"><small>Person to Notify</small></td>
            </tr>
            <tr>
                <td colspan="12" class="border-right">{{$emergency->lastname}}, {{$emergency->firstname}} {{$emergency->middlename}} {{$emergency->extensionname}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="6" class="small-label"><small>Relationship</small></td>
                <td colspan="6" class="small-label"><small>Address</small></td>
            </tr>
            <tr>
                <td colspan="6" class="border-right">{{$emergency->relation}}</td>
                <td colspan="6" class="border-right">{{$emergency->address}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="4" class="small-label"><small>Home Phone</small></td>
                <td colspan="4" class="small-label"><small>Business Telephone</small></td>
                <td colspan="4" class="small-label"><small>Mbbile Phone</small></td>
            </tr>
            <tr>
                <td colspan="4" class="border-right">{{$emergency->phone}}</td>
                <td colspan="4" class="border-right">{{$emergency->business_phone}}</td>
                <td colspan="4" class="border-right">{{$emergency->mobile}}</td>
            </tr>
        </table>
        <h4>AGREEMENT</h4>
        <label>The following must be read and signed by the applicant and her parent(s), guardian or spouse.</label>
        <label>I understand that my application and admission into the Assumption College are subject to the following conditions:</label>
        <ol>
            <li>That it is the responsibility of the applicant to provide all necessary documentary evidence of her qualification and experience;</li>
            <li>That confidential recommendations, interview reports, and statements from members of the Admissions Committee and the Admissions staff will be used solely for purposes of evaluation of this application;</li>
            <li>That contents of confidential appraisals shall not be disclosed to me and members of the family;</li>
            <li>The credentials filed in support of this application which are received by the Admissions Office become the property of the Assumption College and will not be returned to the applicant;</li>
            <li>That all forms distributed by the Admissions Office to elicit information are the property of Assumption College and therefore recognize Assumption College's property and confidentiality rights to the same;</li>
            <li>That I have provided accurate information in this application, and authorize the verification of my credentials;</li>
            <li>That any misrepresentation or omission of facts in my application will justify the denial or application of admission;</li>
            <li>That I will notify the Admissions Office of any change in status stated in this application and supporting documents from date of application to date of formal admission into the College;</li>
            <li>That I agree to comply with the rules, policies, and regulation of the Assumption College when I am accepted.</li>
        </ol>
        <h4>Declaration</h4>
        <label>I have read and understood all sections of this admissions package. I declare that to the best of my knowledge the information supplied in this application and supporting documentation is correct and complete.</label>
        <br><br><br>
        <table>
            <tr>
                <td>________________________________</td>
                <td></td>
                <td>___________________</td>
            </tr>
            <tr>
                <td>Signature of Applicant</td>
                <td></td>
                <td>Date</td>
            </tr>
            <tr>
                <td><br></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>________________________________</td>
                <td></td>
                <td>___________________</td>
            </tr>
            <tr>
                <td>Signature of Parent/Guardian/Spouse</td>
                <td></td>
                <td>Date</td>
            </tr>
        </table>
    </div>
</body>
