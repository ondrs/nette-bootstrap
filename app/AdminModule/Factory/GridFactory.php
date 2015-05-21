<?php

namespace App\AdminModule\Factory;


use App\GridNotExistsException;
use Grido\Translations\FileTranslator;
use Nette\Database\Context;
use Nette\Database\Table\Selection;
use Nette\Utils\Html;
use Nette\Application\UI\Presenter;
use Grido\Grid;
use Grido\Components\Columns;


class GridFactory
{

    /** @var \Nette\Database\Context */
    private $db;

    /** @var \Nette\Application\UI\Presenter */
    private $presenter;


    /**
     * @param Context $db
     */
    public function __construct(Context $db)
    {
        $this->db = $db;
    }


    /**
     * @param Presenter $presenter
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }


    /**
     * @param $name
     * @param Selection $selection
     * @return Grid
     * @throws GridNotExistsException
     */
    public function create($name, Selection $selection)
    {
        $className = '\\App\\AdminModule\\Grid\\' . $name;

        if (!class_exists($className)) {
            throw new GridNotExistsException("Grid class $className does not exist!");
        }

        $presenter = $this->presenter;

        /** @var Grid $grid */
        $grid = new $className($this->db, $selection);

        $grid->setDefaultPerPage(100);


        $grid->setModel($selection)
            ->setTranslator(new FileTranslator('cs'));

        $grid->addActionHref('edit', '')
            ->setIcon('pencil');

        $grid->addActionHref('hide', 'Skrýt')
            ->setCustomRender(function ($row) use ($presenter) {

                $icon = Html::el('i');

                $button = Html::el('a')
                    ->href($presenter->link('hide!', $row->id))
                    ->title('Skrýt');

                if (isset($row->visible)) {
                    $icon->class('fa fa-eye');
                    if ($row->visible) {
                        $button->class('btn btn-default btn-xs btn-mini');
                    } else {
                        $button->class('btn btn-danger btn-xs btn-mini');
                    }
                    $button->setHtml($icon);

                } else {
                    $button->style('display: none');
                }

                return $button;
            });


        $grid->addActionHref('delete', '', 'delete!')
            ->setIcon('times')
            ->setConfirm('Opravdu smazat?');


        return $grid;
    }

}
