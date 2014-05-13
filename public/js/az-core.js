var az =
{
    ajaxPost: function(object, data)       // creation
    {
        $.ajax(
        {
            url: '/api/' + object,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: az.ajaxResult
        })
    },
    ajaxPut: function(object, id, data)    // editing
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'PUT',
            dataType: 'json',
            data: data,
            success: az.ajaxResult
        })
    },
    ajaxDelete: function(object, id)
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'DELETE',
            dataType: 'json',
            success: az.ajaxResult
        })
    },
    ajaxResult: function(json)
    {
        alert(json.errMsg ? json.errMsg : 'Saved')
    }
}