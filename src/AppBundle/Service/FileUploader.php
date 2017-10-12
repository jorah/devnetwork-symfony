<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Description of FileUploader
 *
 * @author linkus
 */
class FileUploader
{
    private $targetsDir;
    private $targetDir;
    private $filePath = null;
    private $mode;

    public function __construct(array $targetDir)
    {
        $this->targetsDir = $targetDir;
    }

    public function set($name)
    {
        if ($name == 'skill') {
            $this->targetDir = $this->targetsDir[0];
        } elseif ($name == 'user') {
            $this->targetDir = $this->targetsDir[1];
        } else {
            die('error FileUploader');
        }
        $this->mode = $name;
        return $this;
    }

    public function check($file_path)
    {
        if (!empty($file_path)) {
            $path = $this->targetDir . '/' . $file_path;

            if (true === file_exists($path)) {

                $this->filePath = $file_path;
                return new File($path);
            }
            return null;
        }
        return null;
    }

    public function upload($file)
    {
        if (is_null($file)) {
            return $this->filePath;
        }
        if (is_a($file, UploadedFile::class)) {
            $this->removeFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->targetDir, $fileName);
            $this->run($this->targetDir.'/'.$fileName);
            return $fileName;
        } else {
            echo 'nop';
        }
        
    }

    protected function removeFile()
    {
        if (is_null($this->filePath)) {
            return;
        }
        $path = $this->targetDir . '/' . $this->filePath;
      
        if (true === file_exists($path)) {
            unlink($path);
        }
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
    
    protected function run($path){
        if($this->mode == 'skill'){
            $cmd = sprintf('convert "%s" -resize 200x200^ -gravity Center -crop 200x200+0+0 +repage "%s"', $path, $path);
            exec($cmd);
        } elseif($this->mode == 'user'){
            $cmd = sprintf('convert "%s" -resize 200x200^ -gravity Center -crop 200x200+0+0 +repage "%s"', $path, $path);
            exec($cmd);
        }
    }

}
