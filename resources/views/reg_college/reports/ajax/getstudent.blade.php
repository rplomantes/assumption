@if(count($college_levels)>0)
        <table class="table table-striped">
    <thead>
        <tr>    
            <th scope="col"><strong>idno</strong></th>
            <th scope="col"><strong>level</strong></th>
        </tr>
    </thead>
    <tbody>
    @foreach($college_levels as $college_level)
    <tr>
        <td>{{$college_level->idno}}</td>
        <td>{{$college_level->level}}</td>
    </tr>
    @endforeach
    </tbody>
    </table>
@else
<h1>record not found</h1>
@endif
