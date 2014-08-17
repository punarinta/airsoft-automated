<?php
require_once 'generic-market.php';

class Frysen_Nu extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->postPage('http://frysen.nu/sokProdukt.php?viewCategory=0', 'sok=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Inga produkter funna!', null))
        {
            return 2;
        }

        $this->grab($html, 'Hittade Produkter', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<li><table width="100%" border="0">', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = 'http://frysen.nu/' . $this->grab($html, '<a href="', '">');
            $item->img = $this->grab($html, 'src="', '" />');
            $item->name = $this->grab($html, 'class="kattitle">', '</span>');
            $item->descr = $item->name;
            $item->id = $this->grab($html, 'Artikel: ', '</span>');
            $item->code = $item->id;

            $item->stock = ($this->grab($html, 'class="lager', '">') == '1') ? 1 : 0;

            $item->price = $this->grab($html, '<h4>', ':');

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Frysen_Nu('Frysen Airsoft', 'UTF-8');
$shop->scan();
echo $shop->jsonp();