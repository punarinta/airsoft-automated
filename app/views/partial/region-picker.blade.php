<?php
$style = isset ($style) ? $style : '';
$prefix = isset ($prefix) ? $prefix : '';
$defaults = isset ($defaults) ? $defaults : array(0,0);

// TODO: compress JS
?>

<div style="{{$style}}">
    <select class="my-select" id="{{$prefix}}country-ddb"></select>
    @if($placement == 'vertical')
    <br/>
    @endif
    <select class="my-select" id="{{$prefix}}region-ddb"></select>
</div>
<script>
    var {{$prefix}}region_picker =
    {
        ddbC: $('#{{$prefix}}country-ddb'),
        ddbR: $('#{{$prefix}}region-ddb'),
        defCountryId: {{$defaults[0]}},
        defRegionId: {{$defaults[1]}},
        callback: null,

        init: function()
        {
            $.get('/api/country', function(json)
            {
                var i, sel, html = '';
                for (i in json.data)
                {
                    sel = (json.data[i].id == {{$prefix}}region_picker.defCountryId) ? 'selected' : ''
                    html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
                }

                {{$prefix}}region_picker.ddbC.html('<option value="0">{{ trans('airsoft.util.any-country') }}</option>' + html)

                if (!{{$prefix}}region_picker.defRegionId)
                {
                    // no default country => get the first from the list
                    defRegionId = json.data[0].id
                }

                {{$prefix}}region_picker.loadDdbR({{$prefix}}region_picker.defCountryId)
            })

            {{$prefix}}region_picker.ddbC.change(function()
            {
                var countryId = $('option:selected', this).val()
                {{$prefix}}region_picker.loadDdbR(countryId, function()
                {
                    if ({{$prefix}}region_picker.callback)
                    {
                        {{$prefix}}region_picker.callback({{$prefix}}region_picker.getLocation())
                    }
                })
            })

            {{$prefix}}region_picker.ddbR.change(function()
            {
                if ({{$prefix}}region_picker.callback)
                {
                    {{$prefix}}region_picker.callback({{$prefix}}region_picker.getLocation())
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
                    sel = (json.data[i].id == {{$prefix}}region_picker.defRegionId) ? 'selected' : ''
                    html += '<option ' + sel + ' value="' + json.data[i].id + '">' + json.data[i].name + '</option>'
                }

                {{$prefix}}region_picker.ddbR.html('<option value="0">{{ trans('airsoft.util.any-region') }}</option>' + html)

                if (callback) callback()
            })
        },

        getLocation: function()
        {
            return [$('option:selected', {{$prefix}}region_picker.ddbC).val(), $('option:selected', {{$prefix}}region_picker.ddbR).val()]
        },

        change: function(callback)
        {
            {{$prefix}}region_picker.callback = callback
        }
    }
    {{$prefix}}region_picker.init()
</script>