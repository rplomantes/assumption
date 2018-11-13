<input type="submit" value="Print All" class="col-sm-12 btn btn-success">
<table class="table table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>Level</th>
            <th>Program</th>
            <th>Print</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <tr>
            <td>{{$number++}}</td>
            <td>{{$list->idno}}</td>
            <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlenmae}} {{$list->extensionname}}</td>
            <td>{{$list->level}}</td>
            <td>{{$list->program_code}}</td>
            <td><a target="_blank" href="{{url('accounting',array('print_exam_permit',$school_year,$period,$exam_period,$list->idno))}}">Print</a></td>
        </tr>
        @endforeach
    </tbody>
</table>