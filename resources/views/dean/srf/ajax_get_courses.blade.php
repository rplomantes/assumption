<label>Course</label>
<select class="form form-control select2" id="course_code" style="width: 100%;">
    <option value="">Select Course</option>
    @foreach($students as $student)
    <option value="{{$student->course_code}}">{{$student->course_code}}-{{$student->course_name}}</option>
    @endforeach
</select>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>