<ul class="nav nav-stacked">
<li><a href="#">Tuition Fee <span class="pull-right">{{number_format($tuition-$tuitionDiscount,2)}}</span></a></li>
<li><a href="#">Miscellaneous Fees <span class="pull-right">{{number_format($misc-$miscDiscount,2)}}</span></a></li>
<li><a href="#">Other Fees <span class="pull-right">{{number_format($other-$otherDiscount,2)}}</span></a></li>
<li><a href="#">Depository Fees <span class="pull-right">{{number_format($depo-$depoDiscount,2)}}</span></a></li>
<li><a href="#">Subject Related Fee <span class="pull-right">{{number_format($srf-$srfDiscount,2)}}</span></a></li>
<li><a href="#"><b>Total School Fees <span class="pull-right">{{number_format($totalFee-$totalDiscount,2)}}</b></span></a></li>
</ul>
<a href="{{url('registrar_college', array('assessment', 'save_assessment', $idno))}}"><button class="col-sm-12 btn btn-success">Save Assessment</button></a>