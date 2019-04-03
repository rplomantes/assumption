<?php
$levels = \App\CtrAcademicProgram::distinct()->orderBy('sort_by', 'asc')->where('academic_type', '!=','College')->get(['level','sort_by']);
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
        </div>
    </div>
</div>