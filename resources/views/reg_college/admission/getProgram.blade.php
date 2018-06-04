@if($applying_for == "Senior High School")
<select class="form form-control" name="strand"> 
    @foreach($ctr_academic_program as $academicprogram)
    <option>{{$academicprogram->strand}}
    </option>
    @endforeach
</select>
@else 
<select class="form form-control" name="program_name"> 
    @foreach($ctr_academic_program as $academicprogram)
    <option>{{$academicprogram->program_name}}        
    </option>
    @endforeach
</select>
@endif
