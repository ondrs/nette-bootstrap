<?php


namespace App\AdminModule\Components\Dropzone;


interface DropzoneControlFactory
{

    /**
     * @return DropzoneControl
     */
    public function create();
}
