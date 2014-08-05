<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 17.2.13
 */


namespace App\AdminModule;
  

use Nette\Application\UI\Form;



class SignInForm extends Form
{    


    public function __construct()
    {

        parent::__construct();

        $this->addText('login', 'Login:')
            ->setRequired();

        $this->addPassword('password', 'Heslo:')
            ->setRequired();

        $this->addSubmit('submit', 'Přihlásit se')
            ->getControlPrototype()
                ->class('btn btn-primary btn-block');

    }

    
}
