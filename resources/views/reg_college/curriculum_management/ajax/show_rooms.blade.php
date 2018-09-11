<div class="form-group">
    <label>Room</label>
    <select id="room" class="form-control select2" style="width: 100%;">
        <option>Select room</option>
        @foreach ($rooms as $rm)
        <option value="{{$rm->room}}">{{$rm->room}}</option>
        @endforeach
    </select>
</div>