
<?php
$curriculum_years = \App\Curriculum::distinct()->where('program_code', $program_code)->get(['curriculum_year']);
$levels = \App\Curriculum::distinct()->where('program_code', $program_code)->orderBy('level')->get(['level']);
$periods = \App\Curriculum::distinct()->where('program_code', $program_code)->orderBy('period')->get(['period']);
$program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first(['program_name']);
$electives = \App\CtrElective::where('program_code', $program_code)->get();
?>
<div class="row">
    <div class="col-md-2">
        <div class="form-group" id="curriculum_year-form">
            <label>Curriculum Year</label>
            <select id="curriculum_year" class="form-control select2" style="width: 100%;">
                <option value=" ">Select Curriculum</option>
                @foreach ($curriculum_years as $curriculum_year)
                <option value="{{$curriculum_year->curriculum_year}}">{{$curriculum_year->curriculum_year}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group" id="level-form">
            <label>Level</label>
            <select id="level" class="form-control select2" style="width: 100%;">
                <option value=" ">Select Level</option>
                @foreach ($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2" id="period-form">
        <div class="form-group">
            <label>Period</label>
            <select id="period" class="form-control select2" style="width: 100%;">
                <option value=" ">Select Period</option>
                @foreach ($periods as $period)
                <option value="{{$period->period}}">{{$period->period}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="col-sm-12">&nbsp;</label>
            <button class="btn btn-success col-sm-12" onclick="getList_tutorials('{{$program_code}}')">Search</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group" id="elective-form">
            <label>Electives</label>
            <select id="elective" class="form-control select2" style="width: 100%;">
                <option value=" ">Select Elective</option>
                @foreach ($electives as $elective)
                <option value="{{$elective->id}}">{{$elective->course_code}} - {{$elective->course_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group" id="submit_elective-form">
            <label>&nbsp;</label>
            <button class="btn btn-success col-sm-12" onclick="add_elective_tutorials(elective.value)">Add Elective</button>
        </div>
    </div>
</div>