<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Select Course and Section</h4>
        </div>
            {{ csrf_field() }}
            <div class="modal-body">
                <div class='form-group'>
                    <label>Select Course</label>
                    <select name="course" id="course" class="form-control select2" style="width: 100%;" required="required" onchange="select_section(school_year.value, period.value, level.value, academic_program.value, this.value)">
                        <option value=" ">Select Course</option>
                        @foreach ($courses as $course)
                        <option value="{{$course->course_code}}">{{$course->course_code}} - {{$course->course_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="show_courses">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" value="View List" data-dismiss="modal" onclick="get_student_list(course.value, schedule_id.value, school_year.value, period.value, level.value, academic_program.value)">View List</button>
            </div>
    </div>
</div>
<script>    
    $(function () {
        $('.select2').select2();
    });
</script>