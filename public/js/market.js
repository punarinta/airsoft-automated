var market =
{
    init: function()
    {
        var go = function()
        {
            var what = $('#inp-search').val().trim()

            if (!what.length)
            {
                az.showModal('Please type in the search term')
            }
            else
            {
                $('#results tbody').html('')
                $('#btn-search').text('Searching...')
                market.search(what)
            }
        }

        $('#btn-search').click(go)
        $('#inp-search').keypress(function(e)
        {
            if(e.keyCode == 13) go()
        })

        $('#results table').on('click', 'tr', function()
        {
        //    az.showModal($(this).find('.hidden').html())
        })
    },

    search: function(what)
    {
        var i, scanCounter = 0

        for (i in stores)
        {
            (function(i)
            {
                $.ajax(
                {
                    url: '/sys/market/scan/' + stores[i].scan + '.php?showNoStock=1&showNoPrice=1&searchText=' + encodeURIComponent(what),
                    type: 'GET',
                    success: function(json)
                    {
                        json = JSON.parse(json.replace(/(\r\n|\n|\r)/gm, "<br>"))

                        var j, item, html, price, img, name
                        for (j in json.body)
                        {
                            item = json.body[j]

                            price = item.price
                            if (price - 0 === 0) price = '&mdash;'

                            if (item.img.length)
                            {
                                img = '<a target="_blank" href="' + item.img + '"><img class="item-icon" title="Open in original size" src="' + item.img + '" onerror=market.reloadImage(this)></a>'
                            }
                            else img = '&mdash;'

                            item.name = item.name.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')
                            item.descr = item.descr.replace(/(&lt;)/gm, '<').replace(/(&gt;)/gm, '>').replace(/(&qqu;ot;)/gm, '"').replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            name = item.name

                            html = '<tr>'
                            html += '<td>' + name + '</td>'
                            html += '<td>' + price + '</td>'
                            html += '<td>' + stores[i].name + '</td>'
                            html += '<td>' + img + '</td>'
                            html += '<td class="hidden">' + item.descr + '</td>'
                            html += '</tr>'

                            console.log(item.descr)

                            $('#results table').show()
                            $('#btn-search').text('Find')

                            $('#results tbody').append(html)
                        }

                        scanCounter++
                        if (scanCounter == stores.length)
                        {
                            $('#btn-search').text('Find')
                        }
                    }
                })
            })(i);
        }
    },

    reloadImage: function(obj)
    {
        $(obj).parent().html('[ reload ]')
    }
}

market.init()