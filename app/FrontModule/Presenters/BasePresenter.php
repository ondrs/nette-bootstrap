<?php

namespace App\FrontModule\Presenters;

use Nette;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var \WebLoader\LoaderFactory @inject */
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
