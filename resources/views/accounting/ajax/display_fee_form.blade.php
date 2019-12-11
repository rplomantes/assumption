<?php
$accounts = \App\ChartOfAccount::all();
?>
@if($type == 9)
<div class="container-fluid">
    <h4>Update Fee</h4>
    <div class="col-md-12">
        <input type="hidden" name="type"  id="type" value="{{$type}}"/>
        <input type="hidden" name="record_id"  id="record_id" value="{{$id}}"/>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Amount</label>
                <input type="text" style="text-align:right" class="form form-control number" name="amount" id="amount"  value="{{$data->per_unit}}"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
            <div class="col-sm-offset-6">
                <button class="form-control btn btn-success" onclick="saveData()">Save</button>
            </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid">
    <h4>Update Fee</h4>
    <div class="col-md-12">
        <input type="hidden" name="type"  id="type" value="{{$type}}"/>
        <input type="hidden" name="record_id"  id="record_id" value="{{$id}}"/>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Accounting Code</label>
                <select class="form form-control select2" name="account" id="account">
                    @foreach($accounts as $account)
                        <option value="{{$account->accounting_code}}" {{($account->accounting_code)== $data->accounting_code? 'selected':''}}>{{$account->accounting_code}} - {{$account->accounting_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control select2" name="category" id="category">
                    <option {{($data->category)== "Tuition Fee"? 'selected':''}}>Tuition Fee</option>
                    <option {{($data->category)== "Miscellaneous Fees"? 'selected':''}}>Miscellaneous Fees</option>
                    <option {{($data->category)== "Other Fees"? 'selected':''}}>Other Fees</option>
                    <option {{($data->category)== "Depository Fees"? 'selected':''}}>Depository Fees</option>
                    <option {{($data->category)== "Foreign Fee"? 'selected':''}}>Foreign Fee</option>
                    <option {{($data->category)== "Family Council"? 'selected':''}}>Family Council</option>
                    <option {{($data->category)== "Acceptance Fee"? 'selected':''}}>Acceptance Fee</option>
                    <option {{($data->category)== "Parent Partnership"? 'selected':''}}>Parent Partnership</option>
                    <option value="SRF" {{($data->category)== "SRF"? 'selected':''}}>SRF Fee</option>
                    <option {{($data->category)== "Additional Fee"? 'selected':''}}>Additional Fee</option>
                    <option {{($data->category)== "Other Miscellaneous"? 'selected':''}}>Other Miscellaneous</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Subsidiary</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"  value="{{$data->subsidiary}}"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Amount</label>
                <input type="text" style="text-align:right" class="form form-control number" name="amount" id="amount"  value="{{$data->amount}}"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
            <div class="col-sm-offset-6">
                <button class="form-control btn btn-success" onclick="saveData()">Save</button>
            </div>
            </div>
        </div>
    </div>
</div>
@endif