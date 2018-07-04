<select class="form form-control" name="program_name"> 
    @foreach($ctr_academic_program as $academicprogram)
    <option>{{$academicprogram->program_name}}        
    </option>
    @endforeach
</select>
