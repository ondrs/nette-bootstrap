<?php


namespace App\AdminModule\Presenters;



class ExamplePresenter extends BasePresenter
{

    protected $presenterTitle = 'Ukázka administrace';


    protected function createComponentGrid()
    {
        return $this->gridFactory->create('ExampleGrid', $this->db->table('example'));
    }


    protected function createComponentForm()
    {
        return $this->formFactory->create('ExampleForm', $this->db->table('example'));
    }
}
