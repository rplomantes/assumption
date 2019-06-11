<select class="form form-control" name="program_name"> 
    @foreach($ctr_academic_program as $academicprogram)
    @if($academicprogram->program_name == "Bachelor of Communication Major in Advertising and Public Relations" or $academicprogram->program_name == "Bachelor of Communication Major in Media Production")
    @else
    <option>{{$academicprogram->program_name}}</option>
    @endif
    @endforeach
</select>
