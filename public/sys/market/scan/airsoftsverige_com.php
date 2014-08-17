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

        $url = 'http://www.airsoftsverige.com/forum/search.php?terms=all&author=&fid%5B%5D=21&sc=1&sf=titleonly&sr=posts&sk=t&sd=d&st=0&ch=-1&t=0&submit=S%C3%B6k&keywords='.$this->query;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $html = curl_exec($this->ch);

        if ($this->grab($html, 'Inga inlägg hittades', null))
        {
            return 2;
        }

        while (1)
        {
            if (!$firstgrab = $this->grab($html, '<div class="postbody">', null))
            {
                break;
            }

            $item = new ScanMarketItem($this->itemsType);

            $item->url = 'http://airsoftsverige.com/forum' . $this->grab($html, '<a href=".', '">');
            $name = strip_tags($this->grab($html, null, '</a></h3>'));
            $name = str_replace('">', '', $name);

            if (!$this->parseForum($name))
            {
                continue;
            }

            $item->name = $name;

            $descr = $this->grab($html, '<div class="content">', '</div>'."\n");
            $item->descr = $descr;

            preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $descr, $result, PREG_PATTERN_ORDER);
            $result = $result[0];

            if (count($result))
            {
                $item->img = $result[0];
            }

            if ($this->pushRow($item) == -1)
            {
                break;
            }
        }

        curl_close($this->ch);

        return 0;
    }
}

$shop = new Airsoftsverige_Com('AirsoftSverige', 'UTF-8');
$shop->scan();
echo $shop->jsonp();