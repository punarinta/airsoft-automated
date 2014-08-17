var aAsc = []
var market =
{
    currentItem: null,
    documentTitle: document.title,

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
        }).keyup(function()
        {
            var q = $(this).val().trim()
            if (!q.length) document.title = market.documentTitle
            else document.title = 'Search for: ' + q
            market.setupHash()
        })

        $('#results table').on('click', 'tr', function()
        {
            if (!$(this).data('item')) return
            market.currentItem = JSON.parse(decodeURIComponent($(this).data('item')))

            $('#item-viewer').show()
                .find('.content').html(market.currentItem.descr).show()
                .find('img').on('error', function()
            {
                $(this).hide()
            })
            $('#results').hide()
        })

        $('#item-viewer .back').click(function()
        {
            $('#results,#item-viewer').toggle()
        })

        $('#item-viewer .open').click(function()
        {
            window.open(market.currentItem.url, '_blank')
        })
        $('#btn-show-filters').click(function()
        {
            $('#filter-area').toggle(400)
        })

        var q = market.getHash('q')
        if (q.length)
        {
            setTimeout(function(){$('#inp-search').val(q);go()}, 500)
        }
    },

    setupHash: function()
    {
        var hash = '', q = $('#inp-search').val().trim()
        if (q.length) hash = 'q=' + q
        location.hash = hash
    },

    getHash: function(k)
    {
        var i, p, h = window.location.hash.substr(1)
        h = h.split('&')
        for (i in h)
        {
            p=h[i].split('=')
            if (p.length == 2 && p[0] == k) return p[1]
        }
        return ''
    },

    search: function(what)
    {
        var i, scanCounter = 0

        for (i in stores)
        {
            (function(i)
            {
                var suffix = '&showNoPrice=1'

                if ($('.show-oos','#controls').is(':checked')) suffix += '&showNoStock=0'
                else suffix += '&showNoStock=1'

                if ($('.min-price','#controls').val().trim().length) suffix += '&minPrice=' + $('.min-price','#controls').val()
                if ($('.max-price','#controls').val().trim().length) suffix += '&maxPrice=' + $('.max-price','#controls').val()

                $.ajax(
                {
                    url: '/sys/market/scan/' + stores[i].scan + '.php?searchText=' + encodeURIComponent(what) + suffix,
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

                            // hellfire
                            item.url = item.url.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"').replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            item.name = item.name.replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')
                            item.descr = item.descr.replace(/(&lt;)/gm, '<').replace(/(&gt;)/gm, '>').replace(/(&qqu;ot;)/gm, '"').replace(/(&amp;)/gm, '&').replace(/(&quot;)/gm, '"')

                            name = item.name

                            if (!item.stock) name += '&nbsp; <span class="oos">(out of stock)</span>'

                            html = '<tr data-item="'+encodeURIComponent(JSON.stringify(item))+'">'
                            html += '<td abbr="' + encodeURIComponent(name) + '">' + name + '</td>'
                            html += '<td abbr="' + price + '">' + price + '</td>'
                            html += '<td abbr="' + encodeURIComponent(stores[i].name) + '">' + stores[i].name + '</td>'
                            html += '<td>' + img + '</td>'
                            html += '</tr>'

                            $('#results table').show()
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
    },

    sortTable: function(that, nr)
    {
        aAsc[nr] = aAsc[nr]=='asc'?'desc':'asc'
        $(that).parent().find('span').html(aAsc[nr]=='asc'?'&#9650;':'&#9660;')
        $('#results>table>tbody>tr').tsort('td:eq('+nr+')[abbr]',{order:aAsc[nr]})
        return false
    }
}

market.init()