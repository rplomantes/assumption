<?php $discount_list = \App\CtrDiscount::where('is_display', 1)->where('academic_type',"!=", "College")->orderBy('id','desc')->get(); ?>
@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th></th><th>w/ Sibling?</th><th>ID No.</th><th>Name</th><th>Status</th><th>TF%</th></tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    @if($list->accesslevel == '0' && $list->academic_type=="BED" && $status->status<=4 || $list->academic_type=="SHS")
    <tr>
        <td><a href="javascript:void(0)" onclick="add_discount_collection('{{$list->idno}}', '{{$status->level}}')"><<</a></td>
        <td>
            <select name="{{$list->idno}}[]" id="{{$list->idno}}">
                <option value="off">No</option>
                <option value="on">Yes</option>
            </select>
        </td>
        <td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @else Not Yet Enrolled @endif
        </td>
        <td>
            <select name="{{$list->idno}}[]" id="tf_discount_{{$list->idno}}">
                @foreach($discount_list as $discount)
                <option value="{{$discount->discount_code}}">{{$discount->discount_code}}%</option>
                @endforeach
            </select>
        </td>
    </tr>
    @endif
    @endforeach
</table>    
@else
<h4>Record Not Found</h4>
@endif

<script>

    function add_discount_collection(idno, level){
    array = {};
    array['idno'] = idno;
    array['subsidiary'] = "Benefit Discount";
    array['level'] = level;
    array['discount_amount'] = $("#"+idno).val();
    array['tf_discount'] = $("#"+"tf_discount_"+idno).val();
    $.ajax({
    type: "GET",
            url: "/bedregistrar/ajax/add_discount_collection",
            data: array,
            success: function (data) {
                location.reload();
            }

    });
    }
</script>

