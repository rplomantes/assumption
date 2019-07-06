<h4 class='display'>Edit Schedule</h4>
<div class="row">
<div class="col-md-4">
    <input type='hidden' id='schedule_id' value='{{$schedule->id}}'>
    <div class="form-group" id="day-form">
        <label>Day</label>
        <select id="edit_day" class="form-control" style="width: 100%;">
            <option value="">Day</option>
            <option value="M" {{$schedule->day=="M"?"selected":""}}>Monday</option>
            <option value="T" {{$schedule->day=="T"?"selected":""}}>Tuesday</option>
            <option value="W" {{$schedule->day=="W"?"selected":""}}>Wednesday</option>
            <option value="Th" {{$schedule->day=="Th"?"selected":""}}>Thursday</option>
            <option value="F" {{$schedule->day=="F"?"selected":""}}>Friday</option>
            <option value="S" {{$schedule->day=="S"?"selected":""}}>Saturday</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group" id="time_start-form">
        <label>Time Start</label>
        <div class="bootstrap-timepicker">
            <div class="input-group">
                <input type="text" class="form-control timepicker" value="{{date('h:i A', strtotime($schedule->time_start))}}" id="edit_time_start">

                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group" id="time_end-form">
        <label>Time End</label>
        <div class="bootstrap-timepicker">
            <div class="input-group">
                <input type="text" class="form-control timepicker" value="{{date('h:i A', strtotime($schedule->time_end))}}" id="edit_time_end">

                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    <label class="col-md-12">&nbsp;</label>
    <button id="room-form" type="button" class="btn btn-default" onclick="edit_available_rooms(schedule_id.value,edit_day.value, edit_time_start.value, edit_time_end.value)"  data-toggle="modal" data-target="#show_rooms2">
        Room
    </button>
</div>
</div>