<?php

namespace App\AdminModule\Factory;


use App\FormNotExistsException;
use App\InvalidArgumentException;
use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\Application\UI\Presenter;


class FormFactory
{

    /** @var \Nette\Database\Context */
    private $db;

    /** @var \Nette\Application\UI\Presenter */
    private $presenter;


    /**
     * @param Context $db
     */
    public function __construct(Context $db)
    {
        $this->db = $db;
    }


    /**
     * @param Presenter $presenter
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }


    /**
     * @param string $name
     * @param Selection $selection
     * @param bool $processForm
     * @return mixed
     * @throws FormNotExistsException
     */
    public function create($name, Selection $selection, $processForm = TRUE)
    {
        $className = '\\App\\AdminModule\\Form\\' . $name;

        if (!class_exists($className)) {
            throw new FormNotExistsException("Form class $className does not exist!");
        }

        if ($this->presenter === NULL) {
            throw new InvalidArgumentException('Presenter must be set!');
        }

        $form = new $className($this->db, $selection);

        if ($processForm) {

            $form->onSuccess[] = function ($form) {

                $form->process();

                if ($form->valid && !$form->hasErrors()) {
                    $this->presenter->flashMessage('Úspěšně uloženo', 'success');
                    $this->presenter->redirect('this');
                }

            };
        }

        return $form;
    }

}

