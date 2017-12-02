<?php
$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
$available_classes = \App\CourseOffering::where('course_code', $course_code)
        ->where('school_year', $school_year->school_year)
        ->where('period', $school_year->period)
        ->where(function($q) use ($instructor_id) {
            $q->where('instructor_id', NULL)
            ->orWhere('instructor_id', "NOT LIKE", $instructor_id);
        })->get();
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Available Classes</h4>
        </div>
        <form method="post" action="{{url ('registrar_college', array('curriculum_management','add_faculty_loading'))}}">
        <div class="modal-body">
            <div class="form-group">
                <label>Available Classes</label>
                {{ csrf_field() }}
                <input type="hidden" value="{{$instructor_id}}" name="instructor_id">
                <select name="course_offering_id" id="room" class="form-control select2" style="width: 100%;" required="required">
                    <option value=" ">Select a Class</option>
                    @foreach ($available_classes as $available_class)
                    <option value="{{$available_class->id}}">{{$available_class->section}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Add"></input>
        </div>
    </form>
    </div>
</div>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>