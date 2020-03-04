<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use stdClass;


class PostPresenter extends Nette\Application\UI\Presenter{
	private $originalString='';
	private $pigLatinString='';

    /**
     * @param Form $form
     * @param stdClass $values
     */
    public function commentFormSucceeded(Form $form, stdClass $values): void{
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
		if ('' === $this->originalString){
            $strings['original']='';
            $strings['pigLatin']='';
			$this->template->post = $strings;
		}
	}

    /**
     * Create form to get word and start translate after hitting submit
     * @return Form
     */
    public function createComponentCommentForm(): Form
    {
		$form = new Form;
		$form->addText('word', 'String pro překlad: ')
			 ->setRequired();
		$form->addSubmit('send', 'Přeložit');
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];

		return $form;
	}


}
