<?php $debit = $credit = 0;?>
<h4>Accounting Entries</h4>
@if(empty($accountings))
<h5>No entries yet.</h5>
@else
<table class="table table-bordered table-responsive table-striped">
    <thead>
    <th colspan="3">&nbsp;</th>
    <th colspan="2" align="center">Amount</th>
    <th></th>
    <tr>
        <th>Account No.</th>
        <th>Account Title</th>
        <th>Particular</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Remove</th>
    </tr>
    </thead>
    <tbody>
        @foreach($accountings as $accounting)
        <tr>
            <td>{{$accounting->accounting_code}}</td>
            <td>{{$accounting->category}}</td>
            <td>{{$accounting->particular}}</td>
            <td>@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
            <td>@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
            <td><a role="button" class="btn btn-danger btn-sm" onclick="removeEntry('{{$accounting->id}}')">Remove</a></td>
        </tr>
        <?php 
        $debit += $accounting->debit;
        $credit += $accounting->credit;
        ?>
        @endforeach
    </tbody>
</table>
<?php $actual_amount = $debit - $credit?>
<h4>Check Details</h4>
<div class="form-group">
    <div class="col-md-4">
        <label>Account Name</label>
        <select class="form form-control select2" width="100%" id='account_name' name='account_name'>
            @foreach($accounting_entry as $accounting_entries)
            <option value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label>Bank</label>
        <input type="text" class="form form-control" width="100%" id='bank' name='bank'>
    </div>
    <div class="col-md-3">
        <label>Check Number</label>
        <input type="text" class="form form-control" width="100%" id='check_no' name='check_no' required>
    </div>

    <div class="col-md-2">
        <label>Check Amount</label>
        <input type="text" class="form-control" id="check_amount" name="check_amount" style="text-align:right" value="{{$actual_amount}}" readonly>
    </div>
</div>
<div class="form-group">
    <div class="col-md-8">
        <label>Remarks</label>
        <textarea class="form form-control" id='description' name='description' row="3"></textarea>
    </div>
    <div class="col-md-4">
        <label>&nbsp;</label>    
        <a class="form-control btn-danger btn" href='{{url('cancel_disbursement',array($reference))}}'><b>Cancel Disbursement</b></a>
    </div>
</div>
@if($actual_amount > 0)
<div class="form-group">
<div class="col-md-12">
<button type="submit" class="form-control btn-success btn"><b>Process Disbursement</b></button>
</div>
</div>
@endif
@endif


