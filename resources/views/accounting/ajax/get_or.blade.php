@if(count($get_receipts)>0)
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Receipt No</th>
            <th>Paid By</th>
            <th>View</th>
            <th></th
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($get_receipts as $get_receipt)
        <tr>
            <td>{{$get_receipt->receipt_no}}</td>
            <td>{{$get_receipt->paid_by}}</td>
            <td><a href="{{url('/cashier', array('viewreceipt',$get_receipt->reference_id))}}">View Receipt</a></td>
            <td><a href="javascript:void(0)" onclick="get_nextPrev('previous','{{$get_receipt->receipt_no}}')"><-previous</a></td>
            <td><a href="javascript:void(0)" onclick="get_nextPrev('next','{{$get_receipt->receipt_no}}')">next-></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h3>Official Receipt Not Found!!!</h3>
@endif

