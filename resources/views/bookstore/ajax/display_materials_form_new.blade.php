<?php
$accounts = \App\ChartOfAccount::all();
?>
<div class="container-fluid">
    <h4>New Item</h4>
    <div class="col-md-12">
        <input type="hidden" name="group_type"  id="type" value="{{$type}}"/>
        @if($type==5)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Particular</label>
                <select class="form form-control select2" name="particular" id="particular">
                    <option>AC P.E. T-Shirt</option>
                    <option>AC P.E. Jogging Pants</option>
                    <option>AC School Socks</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Size</label>
                <input type="text" class="form form-control" name="size" id="size"/>
            </div>
        </div>
        @elseif($type==1)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control select2" name="category" id="category">
                    <option>Books</option>
                    <option>Materials</option>
                    <option>Other Materials</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Subsidiary</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"/>
            </div>
        </div>
        @elseif($type == 2)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Category</label>
                <select class="form form-control select2" name="category" id="category">
                    <option>Materials</option>
                    <option>Other Materials</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Particular</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"/>
            </div>
        </div>
        @endif
        @if($type != 2)
        <div class="form form-group">
            <div class="col-sm-12">
                <label class="form form-label"> Amount</label>
                <input type="text" style="text-align:right" class="form form-control number" name="amount" id="amount"/>
            </div>
        </div>
        @endif
        <div class="form form-group">
            <div class="col-sm-12">
            <div class="col-sm-offset-6">
                <button class="form-control btn btn-success" onclick="saveNewData()">Save</button>
            </div>
            </div>
        </div>
    </div>
</div>