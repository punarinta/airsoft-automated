<?php
require_once 'generic-forum.php';

class Airsoftsverige_Com extends ScanForum
{
    protected $ch;

    protected function scanner()
    {
        $username = 'punarinta';
        $password = 'MyAirsoftsverige';
        $userId  = 0;
        $sessionId = '';
        $forumIds =
        [
            247=>1,250=>1,251=>1,252=>1,253=>1,254=>1,
            248,249,255,256,257,258,259,260,261,
        ];

        $this->categoryManual = true;

        // 1. Connect with my credentials

        $payload = 'username=' . $username . '&password=' . $password . '&redirect=.%2Fucp.php%3Fmode%3Dlogin&sid=' . $sessionId . '&redirect=index.php&login=Logga+in';

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'http://airsoftsverige.com/forum/ucp.php?mode=login');
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        $response = curl_exec($this->ch);
        list ($header, $dummy) = explode("\r\n\r\n", $response, 2);

        $headers = explode("\r\n", $header);
        foreach ($headers as $header)
        {
            $header = explode(': ', $header);
            if ($header[0] == 'Set-Cookie')
            {
                $cookie = $header[1];
                $cookieData = explode(';', $cookie);
                foreach ($cookieData as $atom)
                {
                    $atom = explode('=', $atom);
                    if ($atom[0] == 'phpbb3_pxzxx_u') $userId = $atom[1];
                    if ($atom[0] == 'phpbb3_pxzxx_sid') $sessionId = $atom[1];
                }
            }
        }

        // 2. Configure GET-batch

        curl_setopt($this->ch, CURLOPT_POST, false);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_COOKIE, 'phpbb3_pxzxx_u=' . $userId . ';phpbb3_pxzxx_k=; phpbb3_pxzxx_sid=' . $sessionId );


        // 3. Make GET-requests with the session

        $this->forumInit();

        foreach ($forumIds as $forumId => $category)
        {
            if ($this->category && $this->category != $category)
            {
                continue;
            }

            curl_setopt($this->ch, CURLOPT_URL, 'http://airsoftsverige.com/forum/viewforum.php?f=' . $forumId);
            $html = curl_exec($this->ch);

            // set pointer
            $this->grab($html, '<table class="forums ">', null);

            while (1)
            {
                $item = new ScanMarketItem($this->itemsType);

                if (!$this->grab($html, '<td class="topic">', null))
                {
                    break;
                }

                $url = 'http://airsoftsverige.com/forum' . $this->grab($html, '<a href=".', '" class');
                $title = $this->grab($html, 'topictitle">', '</a>');

                if (!$this->parseForum($title))
                {
                    continue;
                }

                $item->name = $title;
                $item->url = $url;
                $item->id = explode('t=', $url);
                $item->id = (int) end($item->id);
                
                // get description
                $data = $this->readItem($url);

                $item->descr = $data['descr'];
                $item->price = (int) $data['price'];

                if (count($data['links']))
                {
                    $item->img = $data['links'][0];
                }

                if ($this->pushRow($item) == -1)
                {
                    break;
                }
            }
        }

        curl_close($this->ch);

        return 0;
    }

    /**
     * @param $url
     * @return array
     */
    protected function readItem($url)
    {
        $url = str_replace('&amp;', '&', $url);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $html = curl_exec($this->ch);

        $grabber = new ScanMarket('dummy-name');

        $descr = $grabber->grab($html, '<div class="content">', '</div>'."\n");

        preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $descr, $result, PREG_PATTERN_ORDER);
        $result = $result[0];

        return array
        (
            'descr' => $descr,
            'price' => 0,
            'links' => $result,
        );
    }
}

$shop = new Airsoftsverige_Com('AirsoftSverige', 'UTF-8');
$shop->scan();
echo $shop->jsonp();