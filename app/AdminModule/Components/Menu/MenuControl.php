<?php


namespace App\AdminModule\Components\Menu;


use Nette\Application\UI\Control;

class MenuControl extends Control
{

    /** @var MenuGenerator  */
    private $menuGenerator;


    /**
     * @param MenuGenerator $menuGenerator
     */
    public function __construct(MenuGenerator $menuGenerator)
    {
        $this->menuGenerator = $menuGenerator;
    }



    public function render()
    {
        $this->template->menuItems = $this->menuGenerator->createMenu();
        $this->template->presenter = $this->presenter;

        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->render();
    }

}
