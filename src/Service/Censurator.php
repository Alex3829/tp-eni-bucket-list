<?php


namespace App\Service;

class Censurator

{

    public function purify(String $string): String
    {
        $json = file_get_contents('.././public/json/forbidden-words.json');
        $data = json_decode($json, true);

        $censuredWords = $data["mots_interdits"];

        foreach ($censuredWords as $word) {
            $string = str_ireplace($word, '*', $string);
        }

        return $string;
    }
}
