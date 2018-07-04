<label>Select Course</label>
<select name="course" id="course" class="form-control select2" style="width: 100%;" required="required">
    <option value=" ">Select Course</option>
    @foreach ($courses as $course)
    <option value="{{$course->id}}">{{$course->course_code}} - {{$course->course_name}}</option>
    @endforeach
</select>

<script>
    $(function () {
        $('.select2').select2();
    });
</script>