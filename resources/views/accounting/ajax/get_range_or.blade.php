@if(count($get_receipts)>0)
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Date</th>
            <th>Receipt No</th>
            <th>Paid By</th>
            <th>Posted By</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <?php $start = ltrim($start, '0'); ?>
        @for($start; $start <= $end; $start++)
            <?php
            $check_or = \App\Payment::where('receipt_no', "0000$start")->first();
            ?>
            @if(count($check_or)>0)
            <tr>
                <td>{{$check_or->transaction_date}}</td>
                <td>{{$check_or->receipt_no}}</td>
                <td>{{$check_or->paid_by}}</td>
                <td>{{$check_or->posted_by}}</td>
                <td><a href="{{url('/cashier', array('viewreceipt',$check_or->reference_id))}}">View Receipt</a></td>
            </tr>
            @else
            <tr>
                <td>0000{{$start}}</td>
                <td>OR NOT YET USE!</td>
                <td></td>
            </tr>
            @endif
        @endfor
<!--        @foreach($get_receipts as $get_receipt)
        <tr>
            <td>{{$get_receipt->receipt_no}}</td>
            <td>{{$get_receipt->paid_by}}</td>
            <td><a href="{{url('/cashier', array('viewreceipt',$get_receipt->reference_id))}}">View Receipt</a></td>
        </tr>
        @endforeach-->
    </tbody>
</table>
@else
<h3>Official Receipt Not Found!!!</h3>
@endif

