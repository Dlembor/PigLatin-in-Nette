<?php


namespace App\Presenters;


class Translate
{
    /**
     * Translate constructor.
     * @param $word
     */
    public function __construct(&$word) {
        $splitWord = str_split($word);
        $skip=false;
        $consonant=false;
        foreach ($splitWord as $key => $value) {
            if ($this->isVowel($value)){
                if ($key==0){
                    $splitWord[]="'hay";
                    break;
                }

                $skip=true;
                break;
            }else{
                $consonant=true;
                $splitWord[]=$value;
                if(strtoupper($value) === 'Q' && strtoupper($splitWord[$key+1]) === 'U' &&$this->isVowel($splitWord[$key + 2])){
                    $splitWord[]=$splitWord[$key+1];
                    $splitWord[$key+1]= '';
                }
                $splitWord[$key]= '';
            }
        }
        if ($skip || (!$skip&&$consonant)){
            $splitWord[]= '-ay';
        }
        $word = implode('', $splitWord);
    }

    /**
     * @param $c
     * @return bool
     */
    private function  isVowel($c): bool
    {
        switch (strtoupper($c)){
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