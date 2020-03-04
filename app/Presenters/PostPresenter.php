<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class PostPresenter extends Nette\Application\UI\Presenter{
	private $originalString;
	private $pigLatinString;

    /**
     * @param UI\Form $form
     * @param \stdClass $values
     */
    public function commentFormSucceeded(UI\Form $form, \stdClass $values): void{
		
		$this->originalString=$values->word;
		$splitString=preg_split ('/\s+/',trim($values->word));
		foreach ($splitString as $key => $value) {
			new Translate($value);
            $splitString[$key]=$value;
		}
		$this->pigLatinString=implode(' ', $splitString);

        $strings['original']= $this->originalString;
        $strings['pigLatin']=$this->pigLatinString;
		$this->template->post = $strings;
	}

    /**
     * Only to show page for first time
     */
    public function renderShow(): void{
		if ('' == $this->originalString[0]){
            $strings['original']='';
            $strings['pigLatin']='';
			$this->template->post = $strings;
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
