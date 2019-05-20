<?php

function get_name($idno) {
    $names = \App\User::where('idno', $idno)->first();
    return $names->lastname . ", " . $names->firstname . " " . $names->middlename;
}

function get_promotions($idno, $type) {
    $promotions = \App\Promotion::where('idno', $idno)->first();
    if (count($promotions) > 0) {
        if ($type == "level") {
            return $promotions->level;
        } else if($type == "section") {
            return $promotions->section;
        } else {
            return $promotions->level . " - " . $promotions->strand;
        }
    } else {
        return "";
    }
}

$strands = \App\CtrAcademicProgram::selectRaw("distinct strand, strand_name")->where('academic_code', 'SHS')->get();
?>
<form action="{{url('/bedregistrar/update_batch_promotions/')}}" method="post">
{{csrf_field()}}
<input type="hidden" name="level" value="{{$level}}">
<input type="hidden" name="strand" value="{{$strand}}">
<div class="box-header">
    <div class="box-title">Promote to:</div>
</div>
<div class="box-body">
    <div class="col-sm-3">
        <label>Level</label>
        <select class="form form-control" name="promote_level" id="promote_level">
            <option value="">Select Level</option>
            <option>Pre-Kinder</option>
            <option>Kinder</option>
            <option>Grade 1</option>
            <option>Grade 2</option>
            <option>Grade 3</option>
            <option>Grade 4</option>
            <option>Grade 5</option>
            <option>Grade 6</option>
            <option>Grade 7</option>
            <option>Grade 8</option>
            <option>Grade 9</option>
            <option>Grade 10</option>
            <option>Grade 11</option>
            <option>Grade 12</option>
        </select>
    </div>
    <div class="col-sm-3">
        <div class="promote_strandDisplay">
            <label>Strand</label>
            <select class="form form-control" name="promote_strand" id="promote_strand">
                <option value="">Select Strand</option>
                @foreach($strands as $getstrand)
                <option value="{{$getstrand->strand}}">{{$getstrand->strand_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6 pull-right">
        <label>&nbsp;</label>
        <button class="col-sm-12 btn btn-success">Update Promotions</button>
    </div>
</div>
<div class="box-header">
    <div class="box-title">{{$level}} @if($level == "Grade 11" || $level == "Grade 12") {{" - ".$strand}} @endif</div>
</div>
<div class="box-body">
    <div class="col-sm-12">
        <table class="table table-condensed">
            <tr>
                <th>ID Number</th>
                <th>Name</th>
                <th>Section</th>
                <th>Current Promotions</th>
                <th></th>
            </tr>
            @if(count($status)>0)
            @foreach($status as $stat)
            <tr>
                <td>{{$stat->idno}}</td>
                <td>{{get_name($stat->idno)}}</td>
                <td>{{get_promotions($stat->idno, "section")}}</td>
                @if($level == "Grade 11" || $level == "Grade 12")
                <td>{{get_promotions($stat->idno, "strand")}}</td>
                @else
                <td>{{get_promotions($stat->idno, "level")}}</td>
                @endif
                <td><input type="checkbox" name="post[]" value="{{$stat->idno}}" checked/> </td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $(".promote_strandDisplay").fadeOut(300);

        $("#promote_level").on('change', function (e) {
            if ($("#promote_level").val() == "Grade 11" || $("#promote_level").val() == "Grade 12") {
                $(".promote_strandDisplay").fadeIn(300);
            } else {
                $(".promote_strandDisplay").fadeOut(300);
            }
        });
    });
</script>