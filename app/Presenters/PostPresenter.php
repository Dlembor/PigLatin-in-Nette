<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class PostPresenter extends Nette\Application\UI\Presenter{
	public $word=array('', '');


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

    /**
     * @param $word
     * @return string
     */
	private function translate($word): string
    {
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
		return $word;
	}


    /**
     * @param UI\Form $form
     * @param \stdClass $values
     */
    public function commentFormSucceeded(UI\Form $form, \stdClass $values): void{
		
		$this->word[0]=$values->word;
		$splitString=preg_split ('/\s+/',trim($values->word));
		foreach ($splitString as $key => $value) {
			$splitString[$key]=$this->translate($value);

		}

		$this->word[1]=implode(' ', $splitString);
		$this->template->post = $this->word;
	}


    /**
     * Only to show page for first time
     */
    public function renderShow(): void{
		if ('' == $this->word[0]){
			$output=array('', '');
			$this->template->post = $output;

		}
	}

    /**
     * Create form to get word and start translate after hitting submit
     * @return UI\Form
     */
    public function createComponentCommentForm(): \Nette\Application\UI\Form
    {
		$form = new UI\Form;
		$form->addText('word', 'String pro překlad: ')
			 ->setRequired();
		$form->addSubmit('send', 'Přeložit');
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];
		return $form;
	}


}
