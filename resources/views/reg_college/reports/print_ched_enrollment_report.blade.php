<div>  

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td>
@foreach ($students as $student)
 <?php
 $user = \App\User::where('idno', $student->idno)->first();
 $grade_colleges = \App\GradeCollege::where('idno', $student->idno)->where('school_year', $student->school_year)->where('period', $student->period)->where('level', $student->level)->get();
 ?>
{{$user->firstname}}<br>
@foreach ($grade_colleges as $grade_college)
&nbsp;&nbsp;&nbsp;{{$grade_college->course_name}}<br>
@endforeach
@endforeach
        </td>
    </tr>
  </tbody>
</table>
</div>