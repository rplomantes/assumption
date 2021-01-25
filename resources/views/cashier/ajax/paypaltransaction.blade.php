@if($getpayment)
<table class="table">
    <tr><td><strong>ID Number</strong></td><td>{{$getpayment->idno}}</td></tr>
    <tr><td><strong>Name</strong></td><td>{{$getpayment->getFullNameAttribute()}}</td></tr>
    <tr><td><strong>Request ID</strong></td><td>{{$getpayment->request_id}}</td></tr>
    <tr><td><strong>Request Date</strong></td><td>{{$getpayment->request_date}}</td></tr>
    <tr><td><strong>Response Date</strong></td><td>{{$getpayment->response_date}}</td></tr>
    <tr><td><strong>Amount</strong></td><td>{{$getpayment->amount}}</td></tr>
    <tr><td><strong>Response Code</strong></td><td>{{$getpayment->response_code}}</td></tr>
    <tr><td><strong>Response ID</strong></td><td>{{$getpayment->response_id}}</td></tr>
</table>
@else
Transaction not found...
@endif