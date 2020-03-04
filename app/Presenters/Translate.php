<?php


namespace App\Presenters;


class Translate
{
    /**
     * Translate constructor.
     * @param $string
     */
    public function __construct(string &$string) {
        $words = str_split($string);
        $skip=false;
        $consonant=false;
        foreach ($words as $key => $word) {
            if ($this->isVowel($word)){
                if ($key==0){
                    $words[]="'hay";
                    break;
                }
                $skip=true;
                break;
            }else{
                $consonant=true;
                $words[]=$word;

                if($key===0 && strtoupper($word) === 'Q' && strtoupper($words[$key+1]) === 'U' &&$this->isVowel($words[$key + 2])){
                    $words[]=$words[$key+1];
                    $words[$key+1]= '';
                }
                $words[$key]= '';
            }
        }
        if ($skip || (!$skip&&$consonant)){
            $words[]= '-ay';
        }
        $string = implode('', $words);
    }

    /**
     * @param $letter
     * @return bool
     */
    private function  isVowel(string $letter): bool
    {
        switch (strtoupper($letter)){
            case 'A':
            case 'E':
            case 'I':
            case 'O':
            case 'U':
                return true;
            default:
                return false;
        }
    }
}