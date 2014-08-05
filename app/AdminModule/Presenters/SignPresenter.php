<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 17.2.13
 */


namespace App\AdminModule\Presenters;


use App\AdminModule\SignInForm;
use Nette\Security\AuthenticationException;


class SignPresenter extends \App\Presenters\BasePresenter
{

    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Byl jste odhlášen.');
        $this->redirect('in');
    }


    /**
     * Sign in form component factory.
     * @return \Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        $form = new SignInForm();

        $form->onSuccess[] = $this->signInFormSubmitted;

        return $form;
    }


    /**
     * @param SignInForm $form
     */
    public function signInFormSubmitted(SignInForm $form)
    {
        try {
            $values = $form->values;

            $this->user->setExpiration('+ 14 days', FALSE);

            $this->user->login($values->login, $values->password);

            $this->flashMessage('Úspěšně jste se přihlásili', 'success');
            $this->redirect('Default:');

        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
        }
    }


}
