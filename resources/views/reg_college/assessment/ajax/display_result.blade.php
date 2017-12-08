<ul class="nav nav-stacked">
@foreach ($tuition as $tuitions)<li><a href="#">Tuition Fee <span class="pull-right">{{number_format($tuitions->amount,2)}}</span></a></li>@endforeach
@foreach ($misc as $miscs)    <li><a href="#">Miscellaneous Fees <span class="pull-right">{{number_format($miscs->amount,2)}}</span></a></li>@endforeach
@foreach ($other as $others)    <li><a href="#">Other Fees <span class="pull-right">{{number_format($others->amount,2)}}</span></a></li>@endforeach
@foreach ($depo as $depos)    <li><a href="#">Depository Fees <span class="pull-right">{{number_format($depos->amount,2)}}</span></a></li>@endforeach
@foreach ($srf as $srfs)    <li><a href="#">Subject Related Fee <span class="pull-right">{{number_format($srfs->amount,2)}}</span></a></li>@endforeach
<li><a href="#"><b>Total School Fees <span class="pull-right">{{number_format($totalFee,2)}}</b></span></a></li>
</ul>
<a href="{{url('registrar_college', array('assessment', 'save_assessment', $idno))}}"><button class="col-sm-12 btn btn-success">Save Assessment</button></a>