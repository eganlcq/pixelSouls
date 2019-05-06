<?php

namespace App\Service;

class RandomString {

    public static function Generate($pseudo, $length = 20) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i = 0; $i < $length; $i++) {

            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        $randomString .= strval($pseudo);
        return $randomString;
    }

    public static function GenerateToken($length = 20) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i = 0; $i < $length; $i++) {

            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}