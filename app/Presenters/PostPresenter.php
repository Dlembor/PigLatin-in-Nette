<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class PostPresenter extends Nette\Application\UI\Presenter{
	public $word=array("","");

	/********************************************
	* input - one char
	* return - bool if char is vowel
	*********************************************/
	function  isVowel($c){ 
    	return ($c == 'A' || $c == 'E' || $c == 'I' || $c == 'O' || $c == 'U' || 
            $c == 'a' || $c == 'e' || $c == 'i' || $c == 'o' || $c == 'u'); 
	} 

	/********************************************
	* input - word to translate
	* return - word in PigLatin
	*********************************************/
	function Translate($word){
		$split_word = str_split($word);
		$skip=false;
		$consonant=false;
		foreach ($split_word as $key => $value) {
			if ($this->isVowel($value)){
				if ($key==0){
					$split_word[]="'hay";
					break;
				}else{
					$skip=true;
					break;
				}			
			}else{
				$consonant=true;
				$split_word[]=$value;
				if(strtoupper($value)=="Q"&& strtoupper($split_word[$key+1])=="U"&&$this->isVowel($split_word[$key+2])){
					$split_word[]=$split_word[$key+1];
					$split_word[$key+1]="";
				}
				$split_word[$key]="";
			}
		}
		if ($skip || (!$skip&&$consonant)){
			$split_word[]="-ay";
		}
		$word = implode("", $split_word);
		return $word;
	}

	/********************************************
	* input - form datas to show 
	*********************************************/
	public function commentFormSucceeded(UI\Form $form, \stdClass $values): void{
		
		$this->word[0]=$values->word;
		$RozsekanyRadek=preg_split ('/\s+/',trim($values->word));
		foreach ($RozsekanyRadek as $key => $value) {
			$RozsekanyRadek[$key]=$this->Translate($value);

		}

		$this->word[1]=implode(" ", $RozsekanyRadek);
		$this->template->post = $this->word;
	}
	
	/********************************************
	* Only to show page for 1st time
	*********************************************/
	public function renderShow(): void{
		if ($this->word[0]==""){
			$vypis=array("","");
			$this->template->post = $vypis;

		}
	}
	
	/********************************************
	* Create form to get word and start Translate after hitting submit
	* return - form (only for showing what was written)
	*********************************************/
	protected function createComponentCommentForm(){
		$form = new UI\Form; 

		$form->addText('word', 'String pro překlad: ')
			 ->setRequired();
		$form->addSubmit('send', 'Přeložit');
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];
		return $form;
	}


}
