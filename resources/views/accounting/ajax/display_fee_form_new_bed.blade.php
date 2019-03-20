<?php
$accounts = \App\ChartOfAccount::all();
?>
<div class="container-fluid">
    <h4>New Fee</h4>
    <div class="col-md-12">
        <input type="hidden" name="type"  id="type" value="{{$type}}"/>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Accounting Code</label>
                <select class="form form-control select2" name="account" id="account">
                    @foreach($accounts as $account)
                        <option value="{{$account->accounting_code}}">{{$account->accounting_code}} - {{$account->accounting_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control" name="category" id="category">
                    <option>Miscellaneous Fees</option>
                    <option>Other Fees</option>
                    <option>Depository Fees</option>
                    <option>Foreign Fee</option>
                    <option>SRF</option>
                    <option>Other Miscellaneous</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Subsidiary</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Amount</label>
                <input type="text" style="text-align:right" class="form form-control number" name="amount" id="amount"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
            <div class="col-sm-offset-6">
                <button class="form-control btn btn-success" onclick="saveNewData()">Save</button>
            </div>
            </div>
        </div>
    </div>
</div>