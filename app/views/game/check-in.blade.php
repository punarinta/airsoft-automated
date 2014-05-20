@extends('layout')

@section('header')
<style>
#barcode {font-size:36px;width:200px}
#btn-check-in {background:#ff1a00;color:#222;border-color:#cc0000}
#btn-check-in:hover {border-color:#b02b2c}
.ticket-validity {font-size:36px;display:none}
#ticket-valid {color:red}
#ticket-invalid {color:#008000}
#player-list {width:100%}
#player-list td:nth-child(1n+2) {text-align: center}
</style>
@stop

@section('content')
<div class="window-box-1">
    <input type="text" class="my-input" id="barcode" maxlength="10" placeholder="0000000000" autofocus/>
    <div>
        <button id="btn-check-in" class="my-btn">Check-in</button>
        <button id="btn-validate" class="my-btn">Validate</button>
    </div>
    <div id="ticket-valid" class="ticket-validity">Ticket is valid</div>
    <div id="ticket-invalid" class="ticket-validity">Ticket is invalid</div>
    <hr class="my-hr"/>
    <table id="player-list">
        <tr>
            <th>Player</th>
            <th>Pays in cash</th>
            <th>Checked-in</th>
        </tr>
        @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->nick }} [{{ $ticket->team_name }}]</td>
            <td>{{ $ticket->is_cash ? '+' : '–' }}</td>
            <td>{{ $ticket->ticket_status == Ticket::STATUS_CHECKED ? '+' : '–' }}</td>
        </tr>
        @endforeach
    </table>
</div>
<script>
// allow digits only
$('#barcode').bind('change keyup', function()
{
    $(this).val($(this).val().replace(/[^\d]/g, ''))

    var code = $(this).val()

    if (code.length == 10)
    {
        az.ajaxGet('ticket/validate-barcode', code, function(data)
        {
            if (data.is_valid)
            {
                $('#ticket-invalid').hide()
                $('#ticket-valid').show()
            }
            else
            {
                $('#ticket-valid').hide()
                $('#ticket-invalid').show()
            }
        })
    }
})
</script>
@stop