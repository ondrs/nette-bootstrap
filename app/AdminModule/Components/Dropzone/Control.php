<?php
/**
 * Created by PhpStorm.
 * User: Ondra
 * Date: 7.9.14
 * Time: 18:53
 */

namespace App\AdminModule\Components\Dropzone;


use Nette\Database\Context;
use Nette\Http\Request;
use Nette\Utils\Finder;
use ondrs\UploadManager\Upload;

class Control extends \Nette\Application\UI\Control
{

    /** @var \ondrs\UploadManager\Upload */
    private $upload;

    /** @var string */
    private $dir;

    /** @var \Nette\Database\Context */
    private $db;

    /** @var \Nette\Http\Request  */
    private $httpRequest;

    /** @var array */
    public $onDelete;


    /**
     * @param Upload $upload
     * @param Context $db
     * @param Request $httpRequest
     */
    public function __construct(Upload $upload, Context $db, Request $httpRequest)
    {
        $this->upload = $upload;
        $this->db = $db;
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param string $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param \ondrs\UploadManager\Upload $upload
     */
    public function setUpload($upload)
    {
        $this->upload = $upload;
    }

    /**
     * @return \ondrs\UploadManager\Upload
     */
    public function getUpload()
    {
        return $this->upload;
    }


    /**
     * @return array
     */
    public function findFiles()
    {
        $im = $this->upload->getImageManager();
        $dir = $im->getBasePath() . $im->getRelativePath() . '/' . $this->dir;
        $exclude = [];
        $result = [
            'files' => [],
            'images' => [],
        ];

        if(!is_dir($dir)) {
            return $result;
        }

        foreach($im->getDimensions() as $prefix => $arr) {
            $exclude[] = '*' . $prefix . '_*';
        }


        foreach (Finder::findFiles('*')->exclude($exclude)->in($dir) as $file) {
            $arr = [
                'path' => $im->getRelativePath() . '/' . $this->dir,
                'filename' => $file->getFilename(),
            ];

            if($this->isImage($file)) {
                $result['images'][] = $arr;
            } else {
                $result['files'][] = $arr;
            }
        }

        return $result;
    }


    /**
     * @param \SplFileInfo $file
     * @return bool
     */
    public function isImage(\SplFileInfo $file)
    {
        $ext = ['jpg', 'jpeg', 'gif', 'png'];

        return in_array($file->getExtension(), $ext);
    }


    /**
     *
     */
    public function handleUpload()
    {
        $this->upload->filesToDir($this->dir);
        $this->redrawControl();
    }


    /**
     * @param string $filename
     */
    public function handleDelete($filename)
    {
        $this->upload->getImageManager()->delete($this->dir, $filename);
        $this->upload->getFileManager()->delete($this->dir, $filename);

        $this->onDelete($filename);

        $this->redrawControl();
    }


    /**
     *
     */
    public function render()
    {
        if($this->dir === NULL) {
            throw new DropzoneException('Directory is not set');
        }

        $files = $this->findFiles();

        $this->template->files = $files['files'];
        $this->template->images = $files['images'];

        $this->template->setFile(__DIR__ . '/template.latte');
        $this->template->render();
    }

} 
