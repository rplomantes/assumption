<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<style>
    .fc-today {
    background: #FFF !important;
    border: none !important;
    border-top: 1px solid #ddd !important;
    font-weight: bold;
} 
    
</style>


<h1 align="left">Room: {{$selected_room}}</h1>

{!! $calendar->calendar() !!}
{!! $calendar->script() !!}

<script>
</script>