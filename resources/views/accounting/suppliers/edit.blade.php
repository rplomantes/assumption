<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Update Supplier</h4>
        </div>
        <div class="modal-body">
            <form method="post" action="{{url("/accounting/supplier/update")}}">
                {{csrf_field()}}
                <input type="hidden" id="supplier_id" name="supplier_id" value="">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Supplier Name</label>
                        <input name="supplier_name" id="edit_supplier_name" required type="text" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label>TIN #</label>
                        <input name="tin" required id="edit_tin" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input name="address" required id="edit_address" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input name="due_date" required id="edit_due_date" type="date" class="form-control">
                </div>
                <div class="form-group">
                    <button onclick="return confirm('Do you with to Continue?')" type="submit" class="btn btn-flat btn-success btn-block"><i class="fa fa-check-circle-o"></i> Save Changes</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>