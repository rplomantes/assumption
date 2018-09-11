<label>Courses</label>
<select id="course_code" class="form-control select2" style="width: 100%;">
    <option>Select course</option>
    @foreach ($courses as $course)
    <option value='{{$course->course_code}}'>{{$course->course_code}}-{{$course->course_name}}</option>
    @endforeach
</select>

<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>