@extends('layout')

@section('header')
{{ HTML::style('/css/home.css') }}
@stop

@section('content')
<table id="about-table">
    <tr>
        <td>For organizers</td>
        <td>For players</td>
    </tr>
    <tr>
        <td>
            <ul>
                <li>Create a game in a couple of clicks</li>
                <li>Manage your games</li>
                <li>Don't care about tickets</li>
                <li>Don't care about informing everyone</li>
                <li>Save time with automatic game check-in</li>
                <li>Analyse statistics</li>
            </ul>
        </td>
        <td>
            <ul>
                <li>Manage your team</li>
                <li>Safely book tickets</li>
                <li>Assist teammates with it</li>
                <li>Pay with a card or cash</li>
                <li>Receive all logistics via email</li>
                <li>Enjoy automatic game check-in</li>
            </ul>
        </td>
    </tr>
</table>

@stop