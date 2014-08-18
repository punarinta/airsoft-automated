<?php
require_once 'generic-market.php';

class Airsoftstore_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://airsoftstore.se/index.php?route=product/search&filter_name=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Det finns inga produkter', null))
        {
            return 2;
        }

        $this->grab($html, 'Produkter som matchar', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<div class="image"><', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = $this->grab($html, 'href="', '">');
            $item->img = str_replace('175x125', '400x400', str_replace(' ', '%20', $this->grab($html, '<img src="', '" ')));
            $item->name = $this->grab($html, 'title="', '" alt=');
            $item->descr = $this->grab($html, '<div class="description">', '</div>');

            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, '<div class="price">', '<span>'));

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Airsoftstore_Se('Röda Stjärnan', 'UTF-8');
$shop->scan();
echo $shop->jsonp();