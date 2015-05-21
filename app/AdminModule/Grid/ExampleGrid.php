<?php


namespace App\AdminModule\Grid;


class ExampleGrid extends BaseGrid
{

    public function prepare()
    {

        $this->addColumnText('name', 'Název')
            ->setFilterText();

    }
} 
