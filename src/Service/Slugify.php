<?php


namespace App\Service;


class Slugify
{
    public function generate(string $string): string
    {
        $specialsCharacters = [':','!','?','/','\\',';',',','.','§','*','%','$',')','(',']','[','&','=','#','"','|','{','}'];
        $specialsA = ['à','á','â','ã','ä','å'];
        $specialsC = ['ç'];
        $specialsO = ['ð','ò','ó','ô','õ','ö'];
        $specialsU = ['ù','ú','û','ü'];
        $specialsI = ['ì','í','î','ï'];
        $specialsY = ['ý','ÿ'];
        $specialsE = ['è','é','ê','ë'];
        $slug = str_replace($specialsCharacters, '', $string);
        $slug = str_replace($specialsA, 'a', $slug);
        $slug = str_replace($specialsC, 'c', $slug);
        $slug = str_replace($specialsO, 'o', $slug);
        $slug = str_replace($specialsU, 'u', $slug);
        $slug = str_replace($specialsI, 'i', $slug);
        $slug = str_replace($specialsY, 'y', $slug);
        $slug = str_replace($specialsE, 'e', $slug);
        $slug = str_replace('-', ' ', $slug);
        $slug = str_replace('\'', '-', $slug);
        $arr = explode(' ', $slug);
        $arr = array_map('trim', $arr);
        $arr = array_filter($arr);
        $slug = implode('-',$arr);
        return strtolower($slug);
    }
}
