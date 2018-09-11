<label>Section</label>
<select name="section" id="section" class="form-control">
    <option value="">Select Section</option>
    <option>ALL</option>
    @foreach($sections as $section)
    <option>{{$section->section}}</option>
    @endforeach
</select>