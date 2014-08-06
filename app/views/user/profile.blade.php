@extends('layout')

@section('title')
| {{ trans('airsoft.profile.title') }}
@stop

@section('header')
{{ HTML::style('/css/profile.css') }}
{{ HTML::style('/css/pikaday.css') }}
{{ HTML::script('/js/moment.min.js') }}
{{ HTML::script('/js/pikaday.min.js') }}
@stop

@section('content')
<div class="padded-content">
    <fieldset class="my-fieldset" style="display:inline-block">
        <legend>{{ trans('airsoft.profile.head-status') }}</legend>
        @if (Auth::user()->getIsValidated())
        1. {{ trans('airsoft.profile.stat.1a') }}
        @else
        1. {{ sprintf(trans('airsoft.profile.stat.1b'), URL::route('organizer-dashboard')) }}
        @endif
        <br/>
        @if (Auth::user()->getIsEmailValidated())
        2. {{ trans('airsoft.profile.stat.2a') }}
        @else
        2. {{ trans('airsoft.profile.stat.2b') }}
        @endif
        <br/>
        @if ($team_editable && $team_present)
        3. {{ trans('airsoft.profile.stat.3a') }}
        @elseif ($team_present)
        3. {{ trans('airsoft.profile.stat.3b') }}
        @else
        3. {{ trans('airsoft.profile.stat.3c') }}
        @endif
    </fieldset>
    <br/>
    <br/>

    <fieldset class="my-fieldset" style="float:left" id="form-profile">
        <legend>{{ trans('airsoft.profile.head-profile') }}</legend>
        <table>
            <tr>
                <td>{{ trans('airsoft.profile.you-nick') }}:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input nick" value="{{ $nick }}"/></td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.you-team') }}:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/team-picker', ['placement' => 'vertical', 'defaults' => [$team_country_id, $team_region_id, $team_id], 'prefix' => 'me_'])
                    <input type="hidden" class="team-id" value="{{ $team_id }}"/>
                </td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.you-lang') }}:</td>
                <td>&nbsp;</td>
                <td>
                    <select id="locale" class="my-select locale">
                        <option value="en" @if($locale == 'en') selected="selected" @endif>English</option>
                        <option value="se" @if($locale == 'se') selected="selected" @endif>Svenska</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <br/>
                    <i>{{ trans('airsoft.profile.you-org-area') }}:</i>
                </td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.you-bday') }}:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input birth-date" value="{{ $birth_date }}"/></td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.you-phone') }}:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input phone" value="{{ $phone }}" placeholder="+X (XXX) XXXXXXX"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="my-btn save">{{ trans('airsoft.profile.save-profile') }}</a>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="my-fieldset" style="float:left" id="form-team"
    @if (!Auth::user()->getIsEmailValidated())
    disabled="disabled"
    @endif
    >
        <legend>{{ trans('airsoft.profile.head-team') }}</legend>
        <table>
            @if ($team_editable)
            <tr>
                <td>{{ trans('airsoft.profile.team-name') }}:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input name" value="{{ $team_name }}"/></td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.team-location') }}:</td>
                <td>&nbsp;</td>
                <td>
                    @include('partial/region-picker', ['placement' => 'vertical', 'defaults' => [$team_country_id, $team_region_id,], 'prefix' => 'team_'])
                    <input type="hidden" class="team-id" value="{{ $team_id }}"/>
                </td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.team-url') }}:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input url" value="{{ $team_url }}"/></td>
            </tr>

            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="my-btn save">
                        @if ($team_id)
                        {{ trans('airsoft.profile.save-team') }}
                        @else
                        {{ trans('airsoft.profile.create-team') }}
                        @endif
                    </a>
                </td>
            </tr>
            @else
            <tr>
                <td colspan="3"><span class="warn-span">{{ trans('airsoft.profile.not-team-owner') }}</span></td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.team-name') }}:</td>
                <td>&nbsp;</td>
                <td>{{ $team_name }}</td>
            </tr>
            <tr>
                <td>{{ trans('airsoft.profile.team-region') }}:</td>
                <td>&nbsp;</td>
                <td>{{ $team_country_name }}, {{ $team_region_name }}</td>
            </tr>
            @endif
        </table>
    </fieldset>

    <fieldset class="my-fieldset
    @if (!Auth::user()->getIsValidated())
    hidden
    @endif
    " style="float:left" id="form-bank">
        <legend>Bank data</legend>
        <table>
            <tr>
                <td>Account:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input bank-account" value="{{ $bank_account }}"/></td>
            </tr>
            <tr>
                <td>IBAN:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input bank-iban" value="{{ $bank_iban }}"/></td>
            </tr>
            <tr>
                <td>SWIFT:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input bank-swift" value="{{ $bank_swift }}"/></td>
            </tr>
            <tr>
                <td>Bank name:</td>
                <td>&nbsp;</td>
                <td><input type="text" class="my-input bank-name" value="{{ $bank_name }}"/></td>
            </tr>
        </table>
    </fieldset>
</div>
<script>
    $('#form-profile .save').click(function()
    {
        $.ajax(
        {
            url: '/api/user/{{ $user_id }}',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(
            {
                nick: $('#form-profile .nick').val(),
                birth_date: $('#form-profile .birth-date').val(),
                locale: $('#form-profile .locale').val(),
                phone: $('#form-profile .phone').val(),
                team_id: me_team_picker.getTeamId()
            }),
            success: az.ajaxResult
        })

        return false
    })

    $('#form-team .save').click(function()
    {
        @if ($team_id)
        $.ajax(
        {
            url: '/api/team/{{ $team_id }}',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                url: $('#form-team .url').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: az.ajaxResult
        })
        @else

        if (!confirm('{{ trans('airsoft.profile.check-team') }}'))
        {
            return false
        }

        $.ajax(
        {
            url: '/api/team',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(
            {
                name: $('#form-team .name').val(),
                url: $('#form-team .url').val(),
                region_id: team_region_picker.getLocation()[1]
            }),
            success: az.ajaxResult
        })
        @endif

        return false
    })

    new Pikaday(
    {
        field: $('#form-profile .birth-date')[0],
        firstDay: 1,
        format: 'YYYY-MM-DD'
    })
</script>
@stop