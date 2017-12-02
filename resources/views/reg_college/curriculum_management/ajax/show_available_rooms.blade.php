<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Available Rooms {{count($is_conflict)}}</h4>
        </div>
        <form method="post" action="{{url ('registrar_college', array('curriculum_management','add_course_schedule'))}}">
        <div class="modal-body">
            <div class="form-group">
                @if (count($is_conflict)<=0)
                @if (count($available_rooms)>0)
                <label>Available Rooms</label>
                {{ csrf_field() }}
                <input type="hidden" value="{{$course_offering_id}}" name="course_offering_id">
                <input type="hidden" value="{{$day}}" name="day">
                <input type="hidden" value="{{$time_start}}" name="time_start">
                <input type="hidden" value="{{$time_end}}" name="time_end">
          
                <select name="room" id="room" class="form-control select2" style="width: 100%;" required="required">
                    <option value=" ">Select Room</option>
                    @foreach ($available_rooms as $available_room)
                    <option value="{{$available_room->room}}">{{$available_room->room}}</option>
                    @endforeach                
                </select>
                @else
                No Room Available
                @endif
                @else
                There is a conlfict in schedule.
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            
            @if (count($is_conflict)<=0)
            @if (count($available_rooms)>0)
            <input type="submit" class="btn btn-primary" value="Save schedule"></input>
            @endif
            @endif
        </div>
    </form>
    </div>
</div>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>