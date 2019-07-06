<h4>Student List</h4>
<table class="table table-condensed">
    <tr>
        <th>No.</th>
        <th>ID Number</th>
        <th>Name</th>
        <th>Program</th>
        <th></th>
    </tr>
    <?php $counter = 1; ?>
    @foreach ($student_lists as $student_list)
    <?php $user = \App\User::where('idno', $student_list->idno)->first(); ?>
    <?php $status = \App\Status::where('idno', $student_list->idno)->first(); ?>
    <tr>
        <td>{{$counter}} <?php $counter = $counter + 1; ?></td>
        <td>{{$student_list->idno}}</td>
        <td>{{$user->lastname}}, {{$user->firstname}}</td>
        <td>{{$status->program_code}}</td>
        <td><a href='javascript:void(0)' onclick='addtosection("{{$student_list->idno}}","{{$course_code}}", schedule_id.value, section.value)'>Add</a></td>
    </tr>
    @endforeach
</table>