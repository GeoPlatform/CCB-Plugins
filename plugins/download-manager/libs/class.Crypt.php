<?php

/**
 * Class Crypt
 * Updated in v2.9.77
 */

namespace WPDM\libs;

class Crypt
{


    public static function encrypt($text)
    {

        if (is_array($text)) $text = serialize($text);
        if(defined('SECURE_AUTH_KEY'))
            $skey = substr(md5(SECURE_AUTH_KEY), 0, 16);
        else
            $skey = substr(md5(NONCE_SALT), 0, 16);

        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($text, $cipher, $skey, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $skey, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

        $ciphertext = str_replace(array('+', '/', '='), array('-', '_', ''), $ciphertext);
        $ciphertext = trim($ciphertext, '=');

        return $ciphertext;
    }

    public static function decrypt($ciphertext)
    {

        if(defined('SECURE_AUTH_KEY'))
            $skey = substr(md5(SECURE_AUTH_KEY), 0, 16);
        else
            $skey = substr(md5(NONCE_SALT), 0, 16);
        $ciphertext = str_replace(array('-', '_'), array('+', '/'), $ciphertext);
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        try {
            $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $skey, $options = OPENSSL_RAW_DATA, $iv);
            $calcmac = hash_hmac('sha256', $ciphertext_raw, $skey, $as_binary = true);
            if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
            {
                return maybe_unserialize(trim($original_plaintext));
            }
        } catch (\Exception $e){
            return '';
        }

        return '';


    }

} 