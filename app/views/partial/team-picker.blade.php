<?php
$style = isset ($style) ? $style : '';
$prefix = isset ($prefix) ? $prefix : '';
$defaults = isset ($defaults) ? $defaults : array(0,0);

// TODO: compress JS
?>
<div style="{{$style}}" class="team-picker">
    <select class="my-select" id="{{$prefix}}country-ddb"></select>
    @if($placement == 'vertical')
    <br/>
    @endif
    <select class="my-select" id="{{$prefix}}region-ddb"></select>
    @if($placement == 'vertical')
    <br/>
    @endif
    <select class="my-select" id="{{$prefix}}team-ddb"></select>
</div>
<script>
    var {{$prefix}}team_picker =
    {
        ddbC: $('#{{$prefix}}country-ddb'),
        ddbR: $('#{{$prefix}}region-ddb'),
        ddbT: $('#{{$prefix}}team-ddb'),
        defCountryId: {{$defaults[0]}},
        defRegionId: {{$defaults[1]}},
        defTeamId: {{$defaults[2]}},
        callback: null,

        init: function()
        {
            $.get('/api/country', function(json)
            {
                var i, sel, html = '';
                for (i in json.data)
                {
                    sel = (json.data[i].id == {{$prefix}}team_picker.defCountryId) ? 'selected' : ''
                    html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
                }

                {{$prefix}}team_picker.ddbC.html('<option value="0">{{ trans('airsoft.util.any-country') }}</option>' + html)

                if (!{{$prefix}}team_picker.defRegionId)
                {
                    // no default country => get the first from the list
                    defRegionId = json.data[0].id
                }

                {{$prefix}}team_picker.loadDdbR({{$prefix}}team_picker.defCountryId)
            })

            {{$prefix}}team_picker.ddbC.change(function()
            {
                var countryId = $('option:selected', this).val()
                {{$prefix}}team_picker.loadDdbR(countryId, function()
                {
                    if ({{$prefix}}team_picker.callback)
                    {
                        {{$prefix}}team_picker.callback({{$prefix}}team_picker.getTeamId())
                    }
                })
            })

            {{$prefix}}team_picker.ddbR.change(function()
            {
                var regionId = $('option:selected', this).val()
                {{$prefix}}team_picker.loadDdbT(regionId, function()
                {
                    if ({{$prefix}}team_picker.callback)
                    {
                        {{$prefix}}team_picker.callback({{$prefix}}team_picker.getTeamId())
                    }
                })
            })

            {{$prefix}}team_picker.ddbT.change(function()
            {
                if ({{$prefix}}team_picker.callback)
                {
                    {{$prefix}}team_picker.callback({{$prefix}}team_picker.getTeamId())
                }
            })
        },

        loadDdbR: function(countryId, callback)
        {
            $.get('/api/region/by-country/' + countryId, function(json)
            {
                var i, html = '', sel
                for (i in json.data)
                {
                    sel = (json.data[i].id == {{$prefix}}team_picker.defRegionId) ? 'selected' : ''
                    html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
                }

                {{$prefix}}team_picker.ddbR.html('<option value="0">{{ trans('airsoft.util.any-region') }}</option>' + html)

                {{$prefix}}team_picker.loadDdbT({{$prefix}}team_picker.defRegionId)
            })
        },

        loadDdbT: function(regionId, callback)
        {
            $.get('/api/team/by-region/' + regionId, function(json)
            {
                var i, html = '', sel
                for (i in json.data)
                {
                    sel = (json.data[i].id == {{$prefix}}team_picker.defTeamId) ? 'selected' : ''
                    html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
                }

                {{$prefix}}team_picker.ddbT.html('<option value="0">{{ trans('airsoft.util.any-team') }}</option>' + html)

                if (callback) callback()
            })
        },

        getLocation: function()
        {
            return [$('option:selected', {{$prefix}}team_picker.ddbC).val(), $('option:selected', {{$prefix}}team_picker.ddbR).val()]
        },

        getTeamId: function()
        {
            return $('option:selected', {{$prefix}}team_picker.ddbT).val()
        },

        change: function(callback)
        {
            {{$prefix}}team_picker.callback = callback
        }
    }
    {{$prefix}}team_picker.init()
</script>