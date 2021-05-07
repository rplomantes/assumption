
<form method="post" action="{{url("/accounting/supplier/update")}}">
    {{csrf_field()}}
    <input type="hidden" id="supplier_id" name="supplier_id" value="{{$data->id}}">
    <div class="form-group row">
        <div class="col-sm-6">
            <label>Supplier Name</label>
            <input name="supplier_name" id="edit_supplier_name" required type="text" class="form-control" value="{{$data->supplier_name}}">
        </div>
        <div class="col-sm-6">
            <label>TIN #</label>
            <input name="tin" required id="edit_tin" type="text" class="form-control" value="{{$data->tin}}">
        </div>
    </div>
    <div class="form-group">
        <label>Address</label>
        <input name="address" required id="edit_address" type="text" class="form-control"value="{{$data->address}}">
    </div>
    <div class="form-group">
        <label>Tax Code</label>
        <select class="form-control" name="tax_code">
            @foreach($tax_codes as $tax_code)
            <option @if($data->tax_code == "$tax_code->tax_code") selected="" @endif>{{$tax_code->tax_code}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <button onclick="return confirm('Do you with to Continue?')" type="submit" class="btn btn-flat btn-success btn-block"><i class="fa fa-check-circle-o"></i> Save Changes</button>
    </div>
</form>