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
                    <option>AC Dengue Attire</option>
                    <option>Colored Shirts</option>
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
                <select class="form form-control select2" name="category" id="category" onchange="display_particular_control(this.value)">
                    <option value="Books">Books/Per Item</option>
                    <option>Materials</option>
                    <option>Other Materials</option>
                </select>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12" id="particular_control">
                <label class="form form-label"> Subsidiary</label>
                <input type="text" class="form form-control" name="subsidiary" id="subsidiary"/>
            </div>
        </div>
        <div class="form form-group">
            <div class="col-sm-12" id="required_control">
                <label class="form form-label"> Is required?</label>
                <select class="form form-control select2" name="is_required" id="is_required">
                    <option value=0>Non-Required</option>
                    <option value=1>Required</option>
                </select>
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
<script>
    
    $("#particular_control").hide();
    $("#required_control").hide();
 $(document).ready(function(){
        
       if($("#category").val()=="Materials" || $("#category").val()=="Other Materials"){
         $("#particular_control").fadeOut(300);
         $("#required_control").fadeOut(300);  
       }else {  
         $("#particular_control").fadeIn(300);
         $("#required_control").fadeIn(300);
        }
});
</script>