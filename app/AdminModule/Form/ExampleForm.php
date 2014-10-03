<?php
/**
 * Created by PhpStorm.
 * User: Ondra
 * Date: 28.8.14
 * Time: 21:29
 */

namespace App\AdminModule\Form;


class ExampleForm extends BaseForm
{

    public function prepare()
    {

        $this->addHidden('image')
            ->setRequired('Zapomněli jste zvolit obrázek');

        $this->addText('name', 'Název článku')
            ->setRequired();

        $this->addTextArea('text', 'Text')
            ->getControlPrototype()
            ->class('tinymce');

        return $this;
    }


}
