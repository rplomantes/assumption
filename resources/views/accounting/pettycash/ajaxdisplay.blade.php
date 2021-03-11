<h4 class="display"></h4>
<table class="table table-condensed dataTable" id="example1">
    <thead>
    <th class="col-md-2">Voucher No.</th>
    <th class="col-md-2">Date</th>
    <th class="col-md-2">Payee Name</th>
    <th class="col-md-3">Remarks</th>
    <th class="col-md-2">Amount</th>
    <th class="col-md-1"></th>
</thead>
<tbody>
    @foreach($lists as $list)
    <tr>
        <td>{{str_pad($list->voucher_no,4,"0",STR_PAD_LEFT)}}</td>
        <td>{{date_format(date_create($list->transaction_date),"F d, Y")}}</td>
        <td>{{$list->payee_name}}</td>
        <td>{{$list->remarks}}</td>
        <td>{{number_format($list->amount,2)}}</td>
        <td><a type="button" href="{{url('/view/disbursement/'.$list->reference_id)}}"><b> View</b></a></td>
    </tr>
    @endforeach
</tbody>
</table>