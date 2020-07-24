<?php $debit = $credit = 0;?>
<h4>Accounting Entries</h4>
@if($accountings->isEmpty())
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
        <th>Subsidiary</th>
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
            <td>{{$accounting->subsidiary}}</td>
            <td>@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
            <td>@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
            <td><a role="button" class="btn btn-danger btn-sm" onclick="removeEntry('{{$accounting->id}}')">Remove</a></td>
        </tr>
        <?php 
        $debit += $accounting->debit;
        $credit += $accounting->credit;
        ?>
        @endforeach
        <tr @if($debit == $credit) style="color:green" @else style="color:red" @endif>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><b>TOTAL</b></td>
            <td><b>{{number_format($debit,2)}}</b></td>
            <td><b>{{number_format($credit,2)}}</b></td>
        </tr>
    </tbody>
</table>
    @if($debit == $credit)
        @if($is_update == 1)
        <?php $voucher = \App\JournalEntry::where('reference_id',$reference)->first()?>
        <div class="form-group">
            <div class="col-md-12">
                <label>Particular</label>
                <textarea class="form form-control" id='description' name='description' row="3" required>{{$voucher->particular}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <a class="form-control btn-danger btn" href='{{url('cancel_edit',array($reference))}}'><b><span>X</span> Cancel Editing</b></a>
            </div>
            <div class="col-md-6">    
                <button type="submit" class="form-control btn-success btn"><span class="fa fa-save"></span> <b>Save Updates</b></button>
            </div>
        </div>
        @else
        <div class="form-group">
            <div class="col-md-12">
                <label>Particular</label>
                <textarea class="form form-control" id='description' name='description' row="3" required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <a class="form-control btn-danger btn" href='{{url('cancel_voucher',array($reference))}}'><b><span>X</span> Cancel Entry</b></a>
            </div>
            <div class="col-md-6">    
                <button type="submit" class="form-control btn-success btn"><span class="fa fa-save"></span> <b>Save Entry</b></button>
            </div>
        </div>
        @endif
    @endif
@endif


