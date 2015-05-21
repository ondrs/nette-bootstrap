<?php


namespace App\AdminModule\Presenters;


use App\AdminModule\Components\Dropzone\DropzoneControl;
use App\AdminModule\Components\Dropzone\DropzoneControlFactory;
use App\AdminModule\Components\Menu\MenuControlFactory;
use App\AdminModule\Factory\FormFactory;
use App\AdminModule\Factory\GridFactory;
use Nette\Database\SqlLiteral;
use ondrs\UploadManager\Upload;
use Tracy\Debugger;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;


abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var LoaderFactory @inject */
    public $webLoader;

    /** @var GridFactory @inject */
    public $gridFactory;

    /** @var FormFactory @inject */
    public $formFactory;

    /** @var MenuControlFactory @inject */
    public $menuControlFactory;

    /** @var  DropzoneControlFactory @inject */
    public $dropzoneControlFactory;

    /** @var Upload @inject */
    public $upload;


    /** @var string */
    protected $presenterTitle;

    /** @var string */
    protected $tableName;


    /**
     *
     */
    protected function startup()
    {
        parent::startup();

        if (!$this->user->isLoggedIn() || !$this->user->isInRole('admin')) {
            $this->flashMessage('Ke vstupu do administrace nemáte oprávnění!', 'danger');
            $this->redirect('Sign:in');
        }

        $explode = explode(':', $this->presenter->name);
        $pieces = preg_split('/(?=[A-Z])/', lcfirst(end($explode)));
        $presenterName = strtolower(implode('_', $pieces));

        $this->tableName = strtolower($presenterName);

        $this->formFactory->setPresenter($this);
        $this->gridFactory->setPresenter($this);
    }


    /**
     * @return CssLoader
     */
    protected function createComponentCss()
    {
        return $this->webLoader->createCssLoader('admin');
    }

    /**
     * @return JavaScriptLoader
     */
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

        $this->template->presenterTitle = $this->presenterTitle;
    }


    /**
     * Default action for adding items
     */
    public function actionCreate()
    {
        $result = $this->db
            ->query('SHOW TABLE STATUS LIKE ?', $this->tableName)
            ->fetch();

        $this['dropzone']->setDir($this->tableName . '/' . $result->Auto_increment);

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

        $this['dropzone']->setDir($this->tableName . '/' . $id);

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
            $this->redirect('this');

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
            $this->redirect('this');

        } catch (\PDOException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            dump($e);
            Debugger::log($e);
        }
    }


    /**
     * @return DropzoneControl
     */
    protected function createComponentDropzone()
    {
        return $this->dropzoneControlFactory->create();
    }


    /**
     * @return \App\AdminModule\Components\Menu\MenuControl
     */
    protected function createComponentMenu()
    {
        return $this->menuControlFactory->create();
    }


}
