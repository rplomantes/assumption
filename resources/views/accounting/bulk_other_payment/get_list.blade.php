<table class="table table-striped">
    <tr>
        <th>ID Number</th>
        <th>Name</th>
        <th>Level-Section</th>
        <th>Action</th>
    </tr>
    @if(count($studentLists)>0)
    @foreach($studentLists as $studentList)
    <tr>
        <td>{{$studentList->idno}}</td>
        <td>{{$studentList->getFullNameAttribute()}}</td>
        <td>{{$studentList->level}}</td>
        <td><a href="javascript:void()" onclick="addStudent({{$studentList->idno}})">>></a></td>
    </tr>
    @endforeach
    @endif
</table>