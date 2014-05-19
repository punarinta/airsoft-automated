<?php

class Bit
{
    /**
     * Performs rotational 15-bit swap in a 32-bit value
     *
     * @param int $in
     * @return int
     */
    static public function swap15($in = 0)
    {
        $out = 0;
        $v32 = 0x100000000;

        for ($pos = 1; $pos <= 32; $pos+=2)
        {
            $revPos = $pos + 15;
            if ($revPos > 32)
            {
                $revPos -= 32;
            }

            if ($in & ($v32 >> $pos)) { $out |= $v32 >> $revPos;}
            if ($in & ($v32 >> $revPos)) { $out |= $v32 >> $pos;}
        }

        return $out;
    }
}