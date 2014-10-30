<?php

namespace App\FrontModule\Presenters;

use Nette;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var \WebLoader\Nette\LoaderFactory @inject */
    public $webLoader;



    /** @return CssLoader */
    protected function createComponentCss()
    {
        return $this->webLoader->createCssLoader('front');
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs()
    {
        return $this->webLoader->createJavaScriptLoader('front');
    }
}
