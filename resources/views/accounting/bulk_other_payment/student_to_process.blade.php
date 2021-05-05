<table class="table table-striped">
    <tr>
        <th>ID Number</th>
        <th>Name</th>
        <th>Level-Section</th>
        <th>Action</th>
    </tr>
    @if(count($currentDatas)>0)
    @foreach($currentDatas as $currentData)
    <tr>
        <td>{{$currentData->idno}}</td>
        <td>{{$currentData->getFullNameAttribute()}}</td>
        <td>{{$currentData->getLevelSection()}}</td>
        <td><a href="javascript:void()" onclick="removeStudent({{$currentData->idno}})">Remove</td>
    </tr>
    @endforeach
    @endif
</table>