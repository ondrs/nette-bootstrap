<?php


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
