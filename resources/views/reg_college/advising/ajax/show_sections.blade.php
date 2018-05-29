<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Available Rooms</h4>
            <form method="post" action="{{url ('registrar_college', array('advising','assign_schedule'))}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="course_id" value="{{$course_id}}">
                    <input type="hidden" name="idno" value="{{$idno}}">
                    <select class='form form-control' name="section">
                        <option>Select Section</option>
                        @foreach ($sections as $section)
                        <option value='{{$section->id}}'>{{$section->section_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Save Schedule"></input>
                </div>
            </form>
        </div>
    </div>