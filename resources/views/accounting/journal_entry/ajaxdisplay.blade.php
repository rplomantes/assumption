<h4 class="display"></h4>
<table class="table table-condensed dataTable">
    <thead>
    <th class="col-md-1">J.V. No.</th>
    <th class="col-md-2">Date</th>
    <th class="col-md-3">Particulars</th>
    <th class="col-md-1">Status</th>
    <th class="col-md-2">Debit</th>
    <th class="col-md-2">Credit</th>
    <th class="col-md-1"></th>
</thead>
<tbody>
    @foreach($lists as $list)
    <?php $entry = \App\Accounting::selectRaw("sum(debit) as debit, sum(credit) as credit")
                    ->where('reference_id', $list->reference_id)->groupBy('reference_id')->first();
    ?>
    <tr>
        <td>{{str_pad($list->voucher_no,4,"0",STR_PAD_LEFT)}}</td>
        <td>{{date_format(date_create($list->transaction_date),"M d, Y")}}</td>
        <td>{{$list->particular}}</td>
        <td>@if($list->is_reverse == 1) Cancelled @else OK @endif</td>
        <td>{{number_format($entry->debit,2)}}</td>
        <td>{{number_format($entry->credit,2)}}</td>
        <td><a type="button" href="{{url('/view/journal_voucher/'.$list->reference_id)}}"><b> View</b></a></td>
    </tr>
    @endforeach
</tbody>
</table>