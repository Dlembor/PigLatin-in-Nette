<?php

declare(strict_types=1); 

namespace App\Presenters;

use Nette;
use Nette\Application\UI;


class PostPresenter extends Nette\Application\UI\Presenter
{
	public function renderShow($promena): void
	{
			$this->template->post = $promena;
		}
	}
}