<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Select Section and Course</h4>
        </div>
            {{ csrf_field() }}
            <div class="modal-body">
                <div class='form-group'>
                    
                    <label>Select Section</label>
                    <select name='section' id='section' class='form-control select2' style='width: 100%;' required="required" onchange="select_course(school_year.value, period.value, level.value, academic_program.value, this.value)">
                        <option value="all">All</option>
                        @foreach ($lists as $list)
                        <option value="{{$list->section}}">{{$list->section}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="show_courses">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" value="View List" data-dismiss="modal" onclick="get_student_list(course.value)">View List</button>
            </div>
    </div>
</div>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>