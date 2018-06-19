<?php
function get_stats($course_code, $level, $school_year, $period){
    $count = \App\CollegeLevel::where('program_code', $course_code)->where('is_audit', 0)->where('status', 3)->where('level', $level)->where('school_year', $school_year)->where('period', $period)->where('level', $level)->get();

    return count($count);
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
    <div style='float: left; margin-left: 275px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>ENROLLMENT STATISTICS</b><br><b>{{$period}}, {{$school_year}} - {{$school_year + 1}}</b></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <thead>
            <tr>
                <th style="text-align: center;" width="70%">PROGRAM / MAJOR</th>
                <th style="text-align: center;" width="20%" colspan="4">YEAR LEVEL</th>
                <th style="text-align: center;" width="10%">TOTAL</th>
            </tr>
            <tr>
                <th style="text-align: center;"></th>
                <th style="text-align: center;">I</th>
                <th style="text-align: center;">II</th>
                <th style="text-align: center;">III</th>
                <th style="text-align: center;">IV</th>
                <th style="text-align: center;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">BACHELOR OF ARTS</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total1 = 0;
            $total1 = get_stats("BAP", "1st Year", $school_year, $period);
            $total2 = get_stats("BAP", "2nd Year", $school_year, $period);
            $total3 = get_stats("BAP", "3rd Year", $school_year, $period);
            $total4 = get_stats("BAP", "4th Year", $school_year, $period);
            $course_total1 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;PSYCHOLOGY</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total1}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF COMMUNICATION</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total2 = 0;
            $total1 = get_stats("BC-BC1", "1st Year", $school_year, $period);
            $total2 = get_stats("BC-BC1", "2nd Year", $school_year, $period);
            $total3 = get_stats("BC-BC1", "3rd Year", $school_year, $period);
            $total4 = get_stats("BC-BC1", "4th Year", $school_year, $period);
            $course_total2 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;ADVERTISING AND PUBLIC RELATIONS</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total2}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total3 = 0;
            $total1 = get_stats("BC-BC2", "1st Year", $school_year, $period);
            $total2 = get_stats("BC-BC2", "2nd Year", $school_year, $period);
            $total3 = get_stats("BC-BC2", "3rd Year", $school_year, $period);
            $total4 = get_stats("BC-BC2", "4th Year", $school_year, $period);
            $course_total3 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;MEDIA PRODUCTION</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total3}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total4 = 0;
            $total1 = get_stats("BC-PA", "1st Year", $school_year, $period);
            $total2 = get_stats("BC-PA", "2nd Year", $school_year, $period);
            $total3 = get_stats("BC-PA", "3rd Year", $school_year, $period);
            $total4 = get_stats("BC-PA", "4th Year", $school_year, $period);
            $course_total4 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;PERFORMING ARTS</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total4}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF ELEMENTARY EDUCATION</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total5 = 0;
            $total1 = get_stats("BEE", "1st Year", $school_year, $period);
            $total2 = get_stats("BEE", "2nd Year", $school_year, $period);
            $total3 = get_stats("BEE", "3rd Year", $school_year, $period);
            $total4 = get_stats("BEE", "4th Year", $school_year, $period);
            $course_total5 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;ELEMENTARY EDUCATION</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total5}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total6 = 0;
            $total1 = get_stats("BEE-PSED", "1st Year", $school_year, $period);
            $total2 = get_stats("BEE-PSED", "2nd Year", $school_year, $period);
            $total3 = get_stats("BEE-PSED", "3rd Year", $school_year, $period);
            $total4 = get_stats("BEE-PSED", "4th Year", $school_year, $period);
            $course_total6 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;PRE-SCHOOL EDUCATION</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total6}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF SECONDARY EDUCATION</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total7 = 0;
            $total1 = get_stats("BSE-SE1", "1st Year", $school_year, $period);
            $total2 = get_stats("BSE-SE1", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSE-SE1", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSE-SE1", "4th Year", $school_year, $period);
            $course_total7 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;ENGLISH</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total7}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF SCIENCE</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total8 = 0;
            $total1 = get_stats("BSA", "1st Year", $school_year, $period);
            $total2 = get_stats("BSA", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSA", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSA", "4th Year", $school_year, $period);
            $course_total8 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;ACCOUNTANCY</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total8}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total9 = 0;
            $total1 = get_stats("BSENT", "1st Year", $school_year, $period);
            $total2 = get_stats("BSENT", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSENT", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSENT", "4th Year", $school_year, $period);
            $course_total9 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;ENTREPRENEURSHIP</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total9}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total10 = 0;
            $total1 = get_stats("BSID", "1st Year", $school_year, $period);
            $total2 = get_stats("BSID", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSID", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSID", "4th Year", $school_year, $period);
            $course_total10 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;INTERIOR DESIGN</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total10}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total11 = 0;
            $total1 = get_stats("BSP", "1st Year", $school_year, $period);
            $total2 = get_stats("BSP", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSP", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSP", "4th Year", $school_year, $period);
            $course_total11 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;PSYCHOLOGY</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total11}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total12 = 0;
            $total1 = get_stats("BSENT-STM", "1st Year", $school_year, $period);
            $total2 = get_stats("BSENT-STM", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSENT-STM", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSENT-STM", "4th Year", $school_year, $period);
            $course_total12 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;WITH SPECIALIZATION IN TOURISM MANAGEMENT</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total12}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total13 = 0;
            $total1 = get_stats("BSBA-CM", "1st Year", $school_year, $period);
            $total2 = get_stats("BSBA-CM", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSBA-CM", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSBA-CM", "4th Year", $school_year, $period);
            $course_total13 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;CORPORATE MANAGEMENT</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total13}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total14 = 0;
            $total1 = get_stats("BSBA-HRDM", "1st Year", $school_year, $period);
            $total2 = get_stats("BSBA-HRDM", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSBA-HRDM", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSBA-HRDM", "4th Year", $school_year, $period);
            $course_total14 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;HUMAN RESOURCE DEVELOPMENT MANAGEMENT</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total14}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total15 = 0;
            $total1 = get_stats("BSBA-IB", "1st Year", $school_year, $period);
            $total2 = get_stats("BSBA-IB", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSBA-IB", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSBA-IB", "4th Year", $school_year, $period);
            $course_total15 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;INTERNATIONAL BUSINESS</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total15}}</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total16 = 0;
            $total1 = get_stats("BSBA-MGT3", "1st Year", $school_year, $period);
            $total2 = get_stats("BSBA-MGT3", "2nd Year", $school_year, $period);
            $total3 = get_stats("BSBA-MGT3", "3rd Year", $school_year, $period);
            $total4 = get_stats("BSBA-MGT3", "4th Year", $school_year, $period);
            $course_total16 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;MARKETING</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total16}}</td>
            </tr>
            <tr>
                <td colspan="6">BACHELOR OF PERFORMING ARTS</td>
            </tr>
            <?php
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $course_total17 = 0;
            $total1 = get_stats("BPA-TT", "1st Year", $school_year, $period);
            $total2 = get_stats("BPA-TT", "2nd Year", $school_year, $period);
            $total3 = get_stats("BPA-TT", "3rd Year", $school_year, $period);
            $total4 = get_stats("BPA-TT", "4th Year", $school_year, $period);
            $course_total17 = $total1 + $total2 + $total3 + $total4;
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;THEATER TRACK</td>
                <td style="text-align: center;">{{$total1}}</td>
                <td style="text-align: center;">{{$total2}}</td>
                <td style="text-align: center;">{{$total3}}</td>
                <td style="text-align: center;">{{$total4}}</td>
                <td style="text-align: center;">{{$course_total17}}</td>
            </tr>
            <tr>
            <?php 
            $grandtotal = $course_total1 + $course_total2 + $course_total3 + $course_total4 + $course_total5 + $course_total6 + $course_total7 + $course_total8 + $course_total9 + $course_total10 + $course_total11 + $course_total12 + $course_total13 + $course_total14 + $course_total15 + $course_total16 + $course_total17;
            ?>    
                <td colspan="5"><strong>GRAND TOTAL</strong></td>
                <td style="text-align: center;"><strong>{{$grandtotal}}</strong></td>
            </tr>
        </tbody>
    </table>
    
    <br>
    <br>
    Certified by:<br><br>
    <b>ROSIE B. SOMERA<br>
    OIC, College Registrar</b>
</div>