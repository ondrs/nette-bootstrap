<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 17.2.13
 */


namespace App\AdminModule\Presenters;


use Nette\Database\SqlLiteral;
use Tracy\Debugger;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var \WebLoader\LoaderFactory @inject */
    public $webLoader;

    /** @var \App\AdminModule\Factory\GridFactory @inject */
    public $gridFactory;

    /** @var \App\AdminModule\Factory\FormFactory @inject */
    public $formFactory;

    /** @var \App\AdminModule\Model\MenuGenerator @inject */
    public $menuGenerator;

    /** @var string */
    public $title;

    /** @var string */
    public $tableName;


    /**
     *
     */
    protected function startup()
    {
        parent::startup();

        if (!$this->user->isLoggedIn() && !$this->user->isInRole('admin')) {
            $this->flashMessage('Ke vstupu do administrace nemáte oprávnění!', 'danger');
            $this->redirect('Sign:in');
        }

        $explode = explode(':', $this->presenter->name);
        $pieces = preg_split('/(?=[A-Z])/', lcfirst(end($explode)));
        $presenterName = strtolower(implode("_", $pieces));

        $this->tableName = strtolower($presenterName);
    }






    /** @return CssLoader */
    protected function createComponentCss()
    {
        return $this->webLoader->createCssLoader('admin');
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs()
    {
        return $this->webLoader->createJavaScriptLoader('admin');
    }


    /**
     *
     */
    public function beforeRender()
    {
        parent::beforeRender();

        $presenter = ucfirst($this->tableName);

        $templatesDir = __DIR__ . '/../templates/';

        $paths = array(
            $templatesDir . $presenter . '/' . $this->view . '.latte',
            $templatesDir . $presenter . '.' . $this->view . '.latte',
        );

        $useDefault = TRUE;

        foreach ($paths as $file) {
            if (is_file($file)) {
                $useDefault = FALSE;
                break;
            }
        }

        if ($useDefault) {
            $this->template->setFile($templatesDir . '_' . $this->view . '.latte');
        }

        $this->template->mainMenu = $this->menuGenerator->createMenu();
        $this->template->presenterTitle = $this->title;
    }


    /**
     * Default action for adding items
     */
    public function actionAdd()
    {
        $result = $this->db->query('SHOW TABLE STATUS LIKE ?', $this->tableName)
            ->fetch();

        $lastId = $result->Auto_increment;

        $this->setView('detail');
    }


    /**
     * Default action for editing items
     *
     * @param int $id
     */
    public function actionEdit($id)
    {
        $row = $this->db->table($this->tableName)
            ->wherePrimary($id)
            ->fetch();

        $this->template->row = $row;

        $this['form']->addHidden('id', $id);
        $this['form']->setDefaults($row);

        $this->setView('detail');
    }


    /**
     * Default handle for deleting items
     *
     * @param int $id
     */
    public function handleDelete($id)
    {
        try {
            $this->db->table($this->tableName)
                ->wherePrimary($id)
                ->delete();

            $this->flashMessage('Úspěšně smazáno');

        } catch (\PDOException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            dump($e);
            Debugger::log($e);
        }

    }


    /**
     * Default handle for hiding items
     *
     * @param int $id
     */
    public function handleHide($id)
    {
        try {

            $this->db->table($this->tableName)
                ->wherePrimary($id)
                ->update(array(
                    new SqlLiteral('visible = !visible')
                ));

            $this->flashMessage('Úspěšně upraveno', 'success');

        } catch (\PDOException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            dump($e);
            Debugger::log($e);
        }
    }


}
