
<form type='get' class="form" action="{{url('/accounting/settings/cashreceipt/update')}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update</h4>
    </div>
    <h1>
        <div class="modal-body" style="font-size: 11pt;">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$details->id}}">
            <div class="form-group">
                <label>Accounting Details</label>
                <select name="accounting_code" class="form form-control">
                    @foreach($chart_of_accounts as $chart)
                    <option value="{{$chart->accounting_code}}" @if($chart->accounting_code == $details->accounting_code) selected="" @endif>{{$chart->accounting_code}}-{{$chart->accounting_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Debit or Credit</label>
                <select name="debit_or_credit" class="form form-control">
                    <option value="debit"  @if("debit" == $details->debit_or_credit) selected="" @endif>Debit</option>
                    <option value="credit" @if("credit" == $details->debit_or_credit) selected="" @endif>Credit</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sort No.</label>
                <input type="number" name="sort_no" class="form form-control" min="1" value="{{$details->sort_no}}">
            </div>
        </div>
    </h1>
    <div class="modal-footer">

        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <input type="submit" value="Update" class="btn btn-success">
    </div>
</form>