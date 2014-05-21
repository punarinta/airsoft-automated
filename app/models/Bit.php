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

    /**
     * Returns an encrypted & utf8-encoded
     *
     * @param $pure_string
     * @param $encryption_key
     * @return string
     */
    static public function encrypt($pure_string, $encryption_key)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        return mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    }

    /**
     * Returns decrypted original string
     *
     * @param $encrypted_string
     * @param $encryption_key
     * @return string
     */
    static public function decrypt($encrypted_string, $encryption_key)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        return mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    }
}