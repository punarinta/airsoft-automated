<?php
require_once 'generic-market.php';

class Combatzone_Se extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://www.combatzone.se/advanced_search_result.php?keywords=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Det finns inga produkter', null))
        {
            return 2;
        }

        $this->grab($html, 'productListing', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, 'product_list_image', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = $this->grab($html, '<a href="', '">');
            $item->img = 'http://www.combatzone.se/' . $this->grab($html, '<img src="', '" ');
            $item->name = $this->grab($html, 'title="', '">');
            $item->descr = $item->name;
            $item->price = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, 'SEK:', ':-'));

            $stock = $this->grab($html, '<img src="', '" ');
            $item->stock = (strpos($stock, 'red') === false) ? 1 : 0;

            $item->id = (int) preg_replace('/[^0-9,]/', '', $this->grab($html, '<br>', ';<br>'));
            $item->code = $item->id;

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Combatzone_Se('Combatzone', 'UTF-8');
$shop->scan();
echo $shop->jsonp();