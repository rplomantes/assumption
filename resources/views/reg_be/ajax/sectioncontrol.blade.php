<label>Select Section</label>
<select id="section" class="form form-control" onchange="popsectionlist()">
    <option>Select Section</option>
    @foreach($sections as $section)
    <option>{{$section->section}}</option>
    @endforeach
</select>    

