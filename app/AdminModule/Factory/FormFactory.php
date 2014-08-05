<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 21.2.13
 */

namespace App\AdminModule\Factory;


use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\Application\UI\Presenter;
use Exception;


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
     * @param $name
     * @param Selection $selection
     * @return mixed
     * @throws FormNotExistsException
     */
    public function create($name, Selection $selection)
    {
        $className = '\\App\\AdminModule\\Form\\' . $name;

        if (!class_exists($className)) {
            throw new FormNotExistsException("Form class $className does not exist!");
        }

        $form = new $className($this->db, $selection);

        $presenter = $this->presenter;
        $form->onSuccess[] = function ($form) use ($presenter) {

            $form->process();

            if ($form->valid && !$form->hasErrors()) {
                $presenter->flashMessage('Úspěšně uloženo', 'success');
                $presenter->redirect('this');
            }

        };

        return $form;
    }

}


class FormNotExistsException extends Exception
{

}
