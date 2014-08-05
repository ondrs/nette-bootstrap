<?php

namespace App\AdminModule;


use Nette\Application\UI\Form;
use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Tracy\Debugger;


abstract class BaseForm extends Form
{

    /** @var \Nette\Database\Context */
    protected $db;

    /** @var \Nette\Database\Table\Selection */
    protected $selection;


    /**
     * @param Context $db
     * @param Selection $selection
     */
    public function __construct(Context $db, Selection $selection)
    {
        parent::__construct();

        $this->db = $db;
        $this->selection = $selection;

        $this->prepare();

        $this->addSubmit('submit', 'Uložit')
            ->getControlPrototype()
            ->class('btn btn-primary');

        // setup form rendering
        $renderer = $this->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

        // make form and controls compatible with Twitter Bootstrap
        $this->getElementPrototype()->class('form-horizontal');

    }


    /*
     * Prepare form buttons
     */
    abstract public function prepare();


    /**
     * Default form handler
     */
    public function process()
    {
        $values = $this->values;

        try {

            if (isset($values['id'])) {

                $id = $values['id'];
                unset($values['id']);

                $this->selection->wherePrimary($id)->update($values);

            } else {
                $this->selection->insert($values);
            }

        } catch (\PDOException $e) {
            $this->addError($e->getMessage());
            dump($e);
            Debugger::log($e);
        }
    }

}
