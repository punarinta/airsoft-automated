@extends('layout')

@section('header')
{{ HTML::style('/css/profile.css') }}
@stop

@section('content')
<div class="padded-content">
    <fieldset class="my-fieldset">
        <legend>Status</legend>
        @if (Auth::user()->getIsValidated())
        You can organize games.
        @else
        You cannot organize games. <a href="{{ URL::route('organizer-dashboard') }}">Read here how to become an organizer.</a>
        @endif
    </fieldset>

    <br/>

    <fieldset class="my-fieldset" style="display: inline-block">
        <legend>Profile</legend>
        <table>
            <tr>
                <td>Nick:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input" name="nick" value=""/></td>
            </tr>
            <tr>
                <td>Birthday:</td>
                <td>&nbsp;</td>
                <td><input type="date" class="my-date" value="" name="birth-date"/></td>
            </tr>
            <tr>
                <td>Team:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/team-picker', ['placement' => 'vertical', 'defaults' => [$team_country, $team_region, $team_id], 'prefix' => 'me_'])
                    <input type="hidden" name="team-id"/>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
@stop