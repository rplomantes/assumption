<?php $ctr = 1; ?>
<div class="panel">
    <div class="panel-body">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Program</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{$ctr++}}</td>
                    <td>{{$student->idno}}</td>
                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    <td>{{$student->program_code}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>