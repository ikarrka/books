<?php



namespace App\Services;

/**
 * Description of AuthorService
 *
 * @author Igor
 */

//use \App\Entity\Author;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {
    private $targetDirectory;
    
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $originalFilename;
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw 'something happens during file upload';
        }

        return $fileName;
    }
    
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
