<?php

require_once 'generic-market.php';

class ScanForum extends ScanMarket
{
	protected $queryArray = [];
	
	protected function forumInit()
	{
        // uses Market's raw query
		$this->queryArray = explode(' ', $this->queryRaw);
		return sizeof($this->queryArray);
	}
	
	protected function parseForum($text, $wtsOnly = false)				// works with UTF-8
	{
		$cmp = mb_strtolower($text);

		if (mb_strpos($cmp, 'såld', 0) !== false) return 0;
		if (mb_strpos($cmp, 'lås', 0) !== false) return 0;
		if (mb_strpos($cmp, 'arkivera', 0) !== false) return 0;

		if ($wtsOnly)
        {
            return 1;
        }
			
		$found = 0;
		foreach ($this->queryArray as $a)
        {
            if (mb_strpos($cmp, mb_strtolower($a), 0) !== false)
            {
                $found++;
            }
        }

		return $found;
	}
}