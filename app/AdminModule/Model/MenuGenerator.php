<?php
/**
 * @author: Ondřej Plšek
 * @email: plsek.o@gmai.com
 * @date: 31.3.13
 */

namespace App\AdminModule\Model;


use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\InvalidArgumentException;
use Nette\Neon\Neon;
use Nette\Object;

class MenuGenerator extends Object
{

    /** @var string */
    private $filename;

    /** @var \Nette\Caching\Cache */
    private $cache;


    /**
     * @param $filename
     * @param IStorage $cacheStorage
     * @throws \Nette\InvalidArgumentException
     */
    public function __construct($filename, IStorage $cacheStorage)
    {
        $this->filename = $filename;

        if (!is_file($filename)) {
            throw new InvalidArgumentException("File $filename does not exists");
        }

        $this->cache = new Cache($cacheStorage, 'Admin');
    }


    /**
     * @return mixed
     */
    public function createMenu()
    {
        $neon = $this->cache->load('menu');

        if ($neon === NULL) {

            $neon = Neon::decode(file_get_contents($this->filename));

            $this->cache->save('menu', $neon, array(
                Cache::FILES => $this->filename,
            ));
        }

        return $neon;
    }
}
