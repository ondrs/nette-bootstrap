<?php


namespace App\AdminModule\Components\Menu;


use Nette\Application\UI\Control;

class MenuControl extends Control
{

    /** @var MenuGenerator */
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

        /**
         * @param array|string $current
         * @param array $currentArgs
         * @return bool
         */
        $this->template->isCurrent = function ($current, $currentArgs = []) {

            if (!is_array($current)) {
                return $this->presenter->isLinkCurrent($current, $currentArgs);
            }

            foreach ($current as $val) {
                if ($this->presenter->isLinkCurrent($val['current'], isset($val['currentArgs']) ? $val['currentArgs'] : [])) {
                    return TRUE;
                }
            }

            return FALSE;
        };

        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->render();
    }

}
