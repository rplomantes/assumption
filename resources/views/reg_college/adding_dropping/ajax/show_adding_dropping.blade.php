
@if(count($adding_droppings)>0)
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Course Code</th>
            <th>Description</th>
            <th>Lec</th>
            <th>Lab</th>
            <th>SRF</th>
            <th>Action</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
    @foreach($adding_droppings as $course)
        <tr>
            <td>{{$course->course_code}}</td>
            <td>{{$course->course_name}}</td>
            <td>{{$course->lec}}</td>
            <td>{{$course->lab}}</td>
            <td>{{$course->srf}}</td>
            <td>{{$course->action}}</td>
            <td><a href="{{url('registrar_college',array('remove_adding_dropping',$idno,$course->id))}}">Remove</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif