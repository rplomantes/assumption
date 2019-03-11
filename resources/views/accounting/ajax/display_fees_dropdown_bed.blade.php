<?php
$levels = \App\CtrAcademicProgram::distinct()->orderBy('level', 'asc')->where('academic_type', 'BED')->get(['level']);
$strands = \App\CtrAcademicProgram::selectRaw("distinct strand, strand_name")->where('academic_code', 'SHS')->get();
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
                    <!--<option></option>-->
                    @foreach ($levels as $level)
                    <option>{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
            @if($type == 2)
            <div class="col-sm-5" id="program_control">
                <label>Select Strand</label>
                <Select name="program_code" id="strand" class="form form-control" onchange="getFees()">
                    <option value=""></option>    
                    @foreach($strands as $strand)
                    <option value="{{$strand->strand}}">{{$strand->strand_name}}</option>
                    @endforeach
                </select> 
            </div>
            @endif
            <div class="col-sm-2" id="period_control">
                <label>&nbsp;</label>
                <button onclick="getFees()" class="col-sm-12 btn btn-success">Get Fees</button>
                
            </div>
        </div>
    </div>
</div>