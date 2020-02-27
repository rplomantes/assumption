<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

<style>
  
    #calendar{
        height:150px;
    }
  
    @media print{
        
        .no-print{
            display: none !important;
        }
        html, body {
            width: 210mm;
            height: 150mm;
            -webkit-print-color-adjust: exact !important;
        }
        
    }
    .fc-time{
        text-align: center;
    }
    .fc-title{
        font-size: 8pt;
        text-align: center;
    }    
    .fc tr {
        border: 2px solid black;
    }
    .fc td {
        border: 2px solid black;
    }
    .fc th {
        border: 2px solid black;
    }
    .fc-today {
      background-color:inherit !important;
    }
    .fc-ltr .fc-axis {
    text-align: center;
    }
    
    .fc tr:nth-child(even) {
    background-color: #f2f2f2;
    background-position: bottom;
    }
    
</style>


<button class="no-print" onclick="myFunction()">Print Room Schedule</button>

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td valign="middle" width="10px"><img style='width:70px; height:70px;' src="{{asset('/images/assumption-logo.png')}}"></td>
        <td valign="middle"><span style="font-size:20pt">Assumption College</span> <br><small> San Lorenzo Village, Makati City</small></td>
    </tr>
</table>

<h1>Instructor: {{$instructor_name}}</h1>
<div id="calendar">
    
</div>
<!--<div style="margin-top: 600px">
Approved by:<br><br><br>

{{env('HED_REGISTRAR')}}
</div>-->

<script>
    $('#calendar').fullCalendar({
        firstDay: 0,
        header: false,
        columnFormat: 'ddd',
        allDaySlot: false,
        defaultView: 'agendaWeek',
        minTime: '07:00:00',
        maxTime: '20:00:00',
        height: 650,
        eventSources: [<?php echo "$event_json"; ?>],
        
            
        eventRender: function(event, element) {
            element.find('div.fc-title').html(element.find('div.fc-title').text()) ;
        }
     });
     
     function myFunction() {
    window.print();
}
</script>