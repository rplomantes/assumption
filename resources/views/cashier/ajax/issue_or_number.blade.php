
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Issue OR Number</h4>
</div>
<form action="{{url('/issue_or_number')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="payment_id" value = "{{$payment_id}}">
    <input type="hidden" name="date_from" value = "{{$date_from}}">
    <input type="hidden" name="date_to" value = "{{$date_to}}">
    <h1>
        <div class="modal-body" style="text-align: center">
            <label>Issue OR Number</label>
            <input type="text" placeholder = "0000******" name="or_number" required="">
            <label>Explanation</label>
            <input type="text" placeholder = "" name="explanation" required="">
        </div>
    </h1>
    <div class="modal-footer">

        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Issue OR Number</button>
    </div>
</form>