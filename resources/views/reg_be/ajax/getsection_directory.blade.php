@if(count($sections)>0)
<select class="form-control select2" id="section" data-placeholder="Select Section">
                  <option>All</option>      
                  @foreach($sections as $section)
                  <option>{{$section->section}}</option>
                  @endforeach
        </select>
@else
<p>No Section Set For This Level</p>
@endif

