<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 21.2.13
 */

namespace App\AdminModule\Grid;


use Grido\Grid;
use Nette\Database\Context;
use Nette\Database\Table\Selection;


abstract class BaseGrid extends Grid
{

    /** @var \Nette\Database\Table\Selection  */
    protected $selection;

	/** @var \Nette\Database\Context */
	protected $db;


    /**
     * @param Context $db
     * @param Selection $selection
     */
    public function __construct(Context $db, Selection $selection)
    {
        parent::__construct();

        $this->db = $db;
        $this->selection = $selection;

        $this->prepare();

    }

    /**
     * Prepare columns
     */
    abstract public function prepare();
}
