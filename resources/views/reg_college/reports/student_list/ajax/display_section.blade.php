<label>Select Section</label>
<select name='section' id='section' class='form-control select2' style='width: 100%;' required="required">
    <option value="all" data-name="all">all</option>
    @foreach ($lists as $list)
    <option value='{{$list->section}}' data-name='{{$list->section_name}}'>{{$list->section_name}}</option>
    @endforeach
</select>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>