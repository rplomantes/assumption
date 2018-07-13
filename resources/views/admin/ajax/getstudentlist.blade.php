@if(count($lists)>0)
<div class='table-responsive'>
    <table class="table table-striped table-condensed">
        <tr><th>ID Number</th><th>Name</th><th>Access Level</th><th>View Information</th></tr>
        @foreach($lists as $list)
        @if($list->accesslevel > '1')
        <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
            <td>
                @switch ($list->accesslevel)
                @case (10)
                    Dean
                @break
                @case (11)
                    Dean MESIL
                @break
                @case (12)
                    Dean MSBMW
                @break
                @case (20)
                    Registrar HED
                @break
                @case (21)
                    Registrar BED
                @break
                @case (30)
                    Accounting Head
                @break
                @case (31)
                    Accounting Staff
                @break
                @case (40)
                    Cashier
                @break
                @case (100)
                    Admin
                @break
                @case (50)
                    Bookstore
                @break
                @case (60)
                    Admission HED
                @break
                @case (61)
                    Admission BED
                @break
                @case (70)
                    Guidance HED
                @break
                @case (71)
                    Guidance BED
                @break
                @case (80)
                    Scholarship HED
                @break
                @endswitch
            </td>
            <td><a href="{{url('/admin', array('view_information', $list->idno))}}">View</a></td></tr>
        @endif
        @endforeach
    </table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

