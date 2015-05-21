<?php


namespace App\AdminModule\Components\Menu;


interface MenuControlFactory
{

    /**
     * @return MenuControl
     */
    public function create();
}
