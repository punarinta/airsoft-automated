var az =
{
    ajaxVerbosity: 2,
    modalCallback : null,

    ajaxGet: function(object, id, callback)
    {
        $.ajax(
        {
            url: '/api/' + object + '/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(json)
            {
                if (json.errMsg) alert(json.errMsg)
                else if (callback) callback(json.data)
            }
        })
    },
    ajaxPost: function(object, data, callback)       // creation
    {
        $.ajax(
        {
            url: '/api/' + object,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(json)
            {
                az.ajaxResult(json)
                if (callback) callback(json.data)
            }
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
        if (az.ajaxVerbosity == 0) return
        if (az.ajaxVerbosity == 1 && json.errMsg) az.showModal(json.errMsg)
        if (az.ajaxVerbosity == 2) az.showModal(json.errMsg ? json.errMsg : 'Operation was successful.')
    },
    showModal: function(content, callback)
    {
        if (callback) az.modalCallback = callback
        $('#modal .content').html(content)
        $('#modal, #modal-background').show()
        $('#modal button').unbind('click').click(az.hideModal).focus()
    },
    hideModal: function()
    {
        $('#modal, #modal-background').hide()
        if (az.modalCallback) az.modalCallback()
    }
}