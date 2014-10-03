<?php
/**
 * Created by PhpStorm.
 * User: Ondra
 * Date: 28.8.14
 * Time: 21:20
 */

namespace App\AdminModule\Grid;


class ExampleGrid extends BaseGrid
{

    public function prepare()
    {

        $this->addColumnText('name', 'Název')
            ->setFilterText();

    }
} 
