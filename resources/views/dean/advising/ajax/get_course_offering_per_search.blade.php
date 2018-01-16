
                    <div class='table-responsive'>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Program</th>
            <th>Units</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($courses_offering as $course_offering)
        <tr>
            <td>{{$course_offering->program_code}}</td>
            <td>{{$course_offering->course_code}}</td>
            <td>{{$course_offering->course_name}}</td>
            <td>{{$course_offering->lec + $course_offering->lab}}</td>
            <td><button class="btn btn-primary" onclick="add_to_course_offered('{{$course_offering->id}}')"><span class="fa fa-plus-circle"></span></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
                    </div>