<?php
function getSubjectName($subject_code) {
    $subject_name = \App\GradeBasicEd::where('subject_code', $subject_code)->first();
    return $subject_name->subject_name;
}
?>
@if(!$subjects->isEmpty())
<form method="post" action="{{url('bedregistrar',array('report_card_sequencing','update'))}}">
    {{csrf_field()}}
    <input type="hidden" name="level" value="{{$level}}">
    <input type="hidden" name="strand" value="{{$strand}}">
    <input type="hidden" name="school_year" value="{{$school_year}}">
    <input type="hidden" name="period" value="{{$period}}">
<table class="table table-condensed">
    <tr>
        <th>Subject_Code</th>
        <th>Subject_Name</th>
        <th>Grouping</th>
        <th>Sort No.</th>
    </tr>
    @foreach($subjects as $key=>$subject)
    <input type="hidden" name="subject_code[{{$key}}]" value="{{$subject->subject_code}}">
    <tr>
        <td>{{$subject->subject_code}}</td>
        <td>{{getSubjectName($subject->subject_code)}}</td>
        <td><input type="text" name="grouping[{{$key}}]" value="{{$subject->report_card_grouping}}" placeholder="Leave this blank if n/a"></td>
        <td><input type="text" name="sort_to[{{$key}}]" value="{{$subject->sort_to}}"></td>
    </tr>
    @endforeach
</table>
    <input type="submit" value="Update Sequencing" class="btn btn-success col-sm-12">
</form>
@endif