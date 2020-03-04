<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class PostPresenter extends Nette\Application\UI\Presenter{
	public $strings=array('', '');
	
    /**
     * @param UI\Form $form
     * @param \stdClass $values
     */
    public function commentFormSucceeded(UI\Form $form, \stdClass $values): void{
		
		$this->strings[0]=$values->word;
		$splitString=preg_split ('/\s+/',trim($values->word));
		foreach ($splitString as $key => $value) {
			new Translate($value);
            $splitString[$key]=$value;

		}

		$this->strings[1]=implode(' ', $splitString);
		$this->template->post = $this->strings;
	}


    /**
     * Only to show page for first time
     */
    public function renderShow(): void{
		if ('' == $this->strings[0]){
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
