<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">New Supplier</h4>
        </div>
        <div class="modal-body">
            <form method="post" action="{{url("/accounting/supplier/create")}}">
                {{csrf_field()}}
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Supplier Name</label>
                        <input name="supplier_name" required type="text" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>TIN #</label>
                        <input name="tin" required type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input name="address" required type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tax Code</label>
                    <select class="form-control" name="tax_code">
                        @foreach($tax_codes as $tax_code)
                        <option>{{$tax_code->tax_code}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button onclick="return confirm('Do you with to Continue?')" type="submit" class="btn btn-flat btn-success btn-block"><i class="fa fa-check-circle-o"></i> Save Changes</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>