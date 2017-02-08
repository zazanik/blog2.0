<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $firstLatter = str_split($fileName);



        $file->move($this->targetDir.'/uploads/'.'/'.$firstLatter[0].'/'.$firstLatter[1], $fileName);

        return 'uploads/'.$firstLatter[0].'/'.$firstLatter[1].'/'.$fileName;
    }
}