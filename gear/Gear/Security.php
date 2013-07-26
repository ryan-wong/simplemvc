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
     * This function is made to create an unique key for you to login to another system of yours.<br/>
     * The final URL will be : http://system.com/key/adasdad2321waewe
     * 
     * @param array $info all information you want another system to know
     * @param string $baseUrl is the url of the connecting system, no backslash at end
     * @param string $backupUrl if the system can't login to system return this url
     * @param string $encryptKey encrypt the key with
     * @param string $apiKey system api key use as verification
     * @return string
     */
    public function buildSecureLoginKeyLink(array $info, $baseUrl, $backupUrl, $encryptKey = "ENCRYPTKEY00", $apiKey = "YOURSYSTEMSECRETKEY") {
        $returnUrl = $backupUrl;
        $info['security'] = $apiKey;
        $info['date'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        $json = json_encode($info);
        $encrypt = encrypt($encryptKey, $json, 'bin2hex');
        $url = "$baseUrl/key/$encrypt";
        if (file_get_contents($url)) {
            $returnUrl = $url;
        }
        return $returnUrl;
    }

    /**
     * 
     * @param string $text
     * @return string
     */
    public function encryptDES($key, $text, $encodeType = '') {
        $encrypted = mcrypt_encrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB);
        if ($encodeType == 'base64') {
            return base64_encode($encrypted);
        }
        if ($encodeType == 'bin2hex') {
            return bin2hex($encrypted);
        }

        return $encrypted;
    }

    /**
     * 
     * @param string $cipher
     * @return string
     */
    public function decryptDES($key, $cipher) {
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $cipher, MCRYPT_MODE_ECB);
        return $decrypted;
    }

    /**
     * Return if the url key is valid. 
     * condition is apikey must match, and key is not older than 1 hour.
     * you can add more conditions if you want
     * @param string $cipher
     * @param string $realApiKey
     * @param string $encryptKey
     * @return boolean
     */
    public function handleSecureLoginKey($cipher, $realApiKey, $encryptKey) {
        $today = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        try {
            $cipher = $this->hextobin($cipher);
            $decrypted = $this->decryptDES($encryptKey, $cipher);
            $data = (array) json_decode(trim($decrypted));
        } catch (Exception $ex) { //incase not valid json or decryption fail            
            return false;
        }
        return ( $realApiKey == $data['security'] && abs($today - $data['date']) <= 3600); // json date only valid for 1 hour
    }

    /**
     * 
     * @param int $length
     * @param boolean $puncuation
     * @return string
     */
    public function randString($length, $puncuation = false) {
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
    /**
     * hex2bin incase old version of php and doesn't work
     * @param string $hexstr
     * @return string
     */
    public function hextobin($hexstr) {
        $n = strlen($hexstr);
        $sbin = "";
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack("H*", $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
                $sbin.=$c;
            }
            $i+=2;
        }
        return $sbin;
    }

}

?>
