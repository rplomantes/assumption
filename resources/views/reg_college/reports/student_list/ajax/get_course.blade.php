
        <link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">

<label>Course</label>
<select class="form form-control select2" name="course_code" id="course_code" onchange="get_schedule(instructor_id.value, school_year.value,period.value,this.value)">
    <option value="">Course</option>
    @foreach($courses as $course)
    <option value="{{$course->course_code}}">{{$course->course_code}}</option>
    @endforeach
</select>


<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
$(function () {
$('.select2').select2();
});
</script>