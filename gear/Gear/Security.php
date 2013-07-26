<?php
/**
 * @author Ryan Wong
 * @version 1.0
 * @package Optional
 * @category Gear
 * @copyright (c) 2013, Ryan Wong
 */
class Gear_Security {

    /**
     * 
     * @param string $text
     * @return string
     */
    function encryptDES($text) {
        $key = 'qFS8LRE6XGZmNx9idHFK6AYC';
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB));
        return $encrypted;
    }

    /**
     * 
     * @param string $cipher
     * @return string
     */
    function decryptDES($cipher) {
        $key = 'qFS8LRE6XGZmNx9idHFK6AYC';
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($cipher), MCRYPT_MODE_ECB);
        return $decrypted;
    }

    /**
     * 
     * @param int $length
     * @param boolean $puncuation
     * @return string
     */
    function randString($length, $puncuation = false) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        if ($puncuation) {
            $chars .= "`~!@#$%^&*()-=+\|[]{};:";
        }
        $str = '';
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

}

?>
