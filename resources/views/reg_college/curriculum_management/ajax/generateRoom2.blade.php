<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<style>
    @media print{
        .no-print{
            display: none !important;
        }
        #generateRoom{
            display: inherit !important;
            overflow: visible !important;
        }
        
    }
</style>

<button class="no-print" onclick="myFunction()">Print Room Schedule</button>
<h1 align="left">Room: {{$selected_room}}</h1>
<div id="generateRoom">
{!! $calendar->calendar() !!}
{!! $calendar->script() !!}
</div>


<script>
    $('.fc-event').css('font-size', '1.85em');
function myFunction() {
    window.print();
}
</script>