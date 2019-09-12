<div style="margin-left: 3in; margin-top: 1.3in">
    <strong>
        @if($student_info->father != NULL && $student_info->mother != NULL)
            MR. & MRS. {{$student_info->father}}
        @elseif($student_info->father == NULL)
            MS. {{$student_ifno->mother}}
        @elseif($student_info->mother == NULL)
            MR. {{$student_ifno->father}}
        @endif
    </strong>
    <br>
    {{$student_info->street}}<br>
    {{$student_info->barangay}}, {{$student_info->municipality}}<br>
    @if(strtoupper($student_info->province) == "N/A")  @else {{$student_info->province}}, @endif {{$student_info->zip}}
</div>