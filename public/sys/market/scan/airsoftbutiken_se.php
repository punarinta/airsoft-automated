<?php
require_once 'generic-market.php';

class Airsoftbutiken_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://airsoftbutiken.se/search.html?SEARCH=1&Search_Text=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Inga varor matchar din sökning', null))
        {
            return 2;
        }

        $this->grab($html, 'varor som matchar', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, 'product-small', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = 'http://airsoftbutiken.se/' . $this->grab($html, '<a href="', '" rel');
            $item->name = $this->grab($html, 'title="', '">');
            $item->img = $this->grab($html, '<img src="', '" ');
            $item->descr = $this->grab($html, '<div class="short">', '</div>');

            $stock = $this->grab($html, '<div class="variant_stock">', '</div>');

            $item->stock = (strpos($stock, 'I Lager') !== false) ? 1 : 0;
            $item->price = (int) trim($this->grab($html, '<div class="price">', '</div>'));

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Airsoftbutiken_Se('Airsoftbutiken', 'UTF-8');
$shop->scan();
echo $shop->jsonp();