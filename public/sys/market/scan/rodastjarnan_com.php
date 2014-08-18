<?php
require_once 'generic-market.php';

class Rodastjarnan_Com extends ScanMarket
{
    protected function scanner()
    {
        if (!$html = $this->getPage('http://www.rodastjarnan.com/sokmotor?searchterms=' . $this->query))
        {
            return 1;
        }

        // check if results present and set pointer if success
        if ($this->grab($html, 'Tyvärr hittades inga sökresultat', null))
        {
            return 2;
        }

        $this->grab($html, 'searchresultstable', null);

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '</tr><tr>', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = $this->grab($html, '<a href="', '">');
            $item->img = $this->grab($html, '<img src="', '">');
            $item->id = $this->grab($html, 'Produkt</td><td>', '</td><td><a');
            $item->code = $item->id;
            $name = $this->grab($html, 'searchproduct">', '</a>');

            if (strpos($name, 'Airsoft: ') === 0) $name = str_replace('Airsoft: ', '', $name);

            $item->name = $name;
            $item->descr = $name;
            $item->price = $this->grab($html, '<nowrap>', ' kr</nowrap>');

            $item->stock = ($this->grab($html, 'Webb: ', ' st') == '0') ? 0 : 1;

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        return 0;
    }
}

$shop = new Rodastjarnan_Com('Röda Stjärnan', 'UTF-8');
$shop->scan();
echo $shop->jsonp();