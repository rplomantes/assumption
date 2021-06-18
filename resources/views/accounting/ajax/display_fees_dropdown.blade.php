<?php
$levels = \App\CtrAcademicProgram::distinct()->orderBy('level', 'asc')->where('academic_type', 'College')->get(['level']);
$strands = \App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code', 'SHS')->get();
$programs = \App\CtrAcademicProgram::selectRaw("distinct program_name, program_code")->where('academic_type', 'College')->get();
?>
<div class="box">
    <div class="box-header">
    </div>
    <div class="box-body form-horizontal">
        <div class="form-group">
            <div class="col-sm-3">
                <label>Select Level</label>
                <select class="form form-control" name="level" id="level" onchange="getFees()">
                    @foreach ($levels as $level)
                    <option>{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-sm-3" id="period_control">
                <label>Period</label>
                <select name="period" class="form form-control" id="period" onchange="getFees()">
                    <option></option>
                    <option>1st Semester</option>
                    <option>2nd Semester</option>
                    <option>Summer</option>
                </select>
            </div>
        </div>
    </div>
</div>