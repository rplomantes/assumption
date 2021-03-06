
@if(count($courses)>0 || count($electives)>0)
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Course Code</th>
            <th>Description</th>
            <th>Units</th>
            <th>SRF</th>
            <th>Lab Fee</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($courses as $course)
        <tr>
            <td>{{$course->course_code}}</td>
            <td>{{$course->course_name}}</td>
            <td>{{$course->lec + $course->lab}}</td>
            <td>{{$course->srf}}</td>
            <td>{{$course->lab_fee}}</td>
            <td><a href="javascript:void(0)" onclick="add_course('{{$idno}}','{{$course->course_code}}', 'course')">Add</a></td>
        </tr>
    @endforeach
    
    @foreach($electives as $elective)
        <tr>
            <td>{{$elective->course_code}}</td>
            <td>{{$elective->course_name}}</td>
            <td>{{$elective->lec + $elective->lab}}</td>
            <td>{{$elective->srf}}</td>
            <td>{{$elective->lab_fee}}</td>
            <td><a href="javascript:void(0)" onclick="add_course('{{$idno}}','{{$elective->course_code}}', 'elective')">Add</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif