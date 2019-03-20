<?php
$accounts = \App\ChartOfAccount::all();
?>
<div class="container-fluid">
    <h4>Update Fee</h4>
    <div class="col-md-12">
        <input type="hidden" name="group_type"  id="group_type" value="{{$type}}"/>
        <input type="hidden" name="record_id"  id="record_id" value="{{$id}}"/>
        @if($type == 5)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Particular</label>
                <select class="form form-control select2" name="particular" id="particular">
                    <option {{($data->particular)== "AC P.E. T-Shirt"? 'selected':''}}>AC P.E. T-Shirt</option>
                    <option {{($data->particular)== "AC P.E. Jogging Pants"? 'selected':''}}>AC P.E. Jogging Pants</option>
                    <option {{($data->particular)== "AC School Socks"? 'selected':''}}>AC School Socks</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Size</label>
                <input type="text" class="form form-control" name="size" id="size"  value="{{$data->size}}"/>
            </div>
        </div>
        @elseif($type == 1)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control select2" name="category" id="category">
                    <option {{($data->category)== "Books"? 'selected':''}}>Books</option>
                    <option {{($data->category)== "Materials"? 'selected':''}}>Materials</option>
                    <option {{($data->category)== "Other Materials"? 'selected':''}}>Other Materials</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Subsidiary</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"  value="{{$data->subsidiary}}"/>
            </div>
        </div>
        @elseif($type == 2)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control select2" name="category" id="category">
                    <option {{($data->category)== "Materials"? 'selected':''}}>Materials</option>
                    <option {{($data->category)== "Other Materials"? 'selected':''}}>Other Materials</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Particular</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"  value="{{$data->particular}}"/>
            </div>
        </div>
        @endif
        @if($type != 2)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Amount</label>
                <input type="text" style="text-align:right" class="form form-control number" name="amount" id="amount"  value="{{$data->amount}}"/>
            </div>
        </div>
        @endif
        <div class="form form-group">
            <div class="col-sm-12">
            <div class="col-sm-offset-6">
                <button class="form-control btn btn-success" onclick="saveData()">Save</button>
            </div>
            </div>
        </div>
    </div>
</div>