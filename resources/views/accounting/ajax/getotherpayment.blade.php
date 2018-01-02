@if(count($search)>0)
<table class="table table-responsive"><tr><th>Particular</th><th>Accounting Code</th><th>Accounting Name</th><th>Remove</th></tr>
    @foreach($search as $other_payment)
        <tr><td>{{$other_payment->subsidiary}}</td><td>{{$other_payment->accounting_code}}</td><td>{{$other_payment->accounting_name}}</td>
            <td><a href="{{url('/accounting',array('remove_set_other_payment',$other_payment->id))}}" onclick="return confirm('Are You Sure?')">Remove</a></td></tr>
    @endforeach
</table>    
@else
<h3>Record Not Found!!!</h3>
@endif

