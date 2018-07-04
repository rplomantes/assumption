
<div class='table-responsive'>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Course Name</th>
            <th>Lec</th>
            <th>Lab</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($curricula as $curriculum)
        <tr>
            <td>{{$curriculum->course_code}}</td>
            <td>{{$curriculum->course_name}}</td>
            <td>{{$curriculum->lec}}</td>
            <td>{{$curriculum->lab}}</td>
            <td><button class="btn btn-primary" onclick="add_to_course_offered('{{$curriculum->id}}')"><span class="fa fa-plus-circle"></span></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
                    </div>
<button class='btn btn-success col-sm-12' onclick="addallcourses('{{$level}}', period.value,'{{$program_code}}','{{$curriculum_year}}')">ADD ALL COURSES</button>