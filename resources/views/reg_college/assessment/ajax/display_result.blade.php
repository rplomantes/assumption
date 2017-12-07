<?php
        
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
$totalFee = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->sum('amount');
//$tuition = \App\Ledger::where('category', 'Tuition Fee')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->sum('amount')->get();
$tuition = \App\Ledger::groupBy(array('category'))->where('category', 'Tuition Fees Receivable')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->selectRaw('category, sum(amount) as amount')->get();
$misc = \App\Ledger::groupBy(array('category'))->where('category', 'Miscellaneous Fees')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->selectRaw('category, sum(amount) as amount')->get();
$other = \App\Ledger::groupBy(array('category'))->where('category', 'Other Fees')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->selectRaw('category, sum(amount) as amount')->get();
$depo = \App\Ledger::groupBy(array('category'))->where('category', 'Depository Fees')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->selectRaw('category, sum(amount) as amount')->get();
$srf = \App\Ledger::groupBy(array('category'))->where('category', 'Subject Related Fee')->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->selectRaw('category, sum(amount) as amount')->get();
?>
<ul class="nav nav-stacked">
@foreach ($tuition as $tuitions)<li><a href="#">Tuition Fee <span class="pull-right">{{number_format($tuitions->amount,2)}}</span></a></li>@endforeach
@foreach ($misc as $miscs)    <li><a href="#">Miscellaneous Fees <span class="pull-right">{{number_format($miscs->amount,2)}}</span></a></li>@endforeach
@foreach ($other as $others)    <li><a href="#">Other Fees <span class="pull-right">{{number_format($others->amount,2)}}</span></a></li>@endforeach
@foreach ($depo as $depos)    <li><a href="#">Depository Fees <span class="pull-right">{{number_format($depos->amount,2)}}</span></a></li>@endforeach
@foreach ($srf as $srfs)    <li><a href="#">Subject Related Fee <span class="pull-right">{{number_format($srfs->amount,2)}}</span></a></li>@endforeach
<li><a href="#"><b>Total School Fees <span class="pull-right">{{number_format($totalFee,2)}}</b></span></a></li>
</ul>

<!--select `category`,sum(`amount`) from `ledgers` where `idno` = '5a24f5a983d01' and  `category` = 'Tuition Fee' and `school_year` = 2017 and `period` = "1st Semester" group by `category`-->