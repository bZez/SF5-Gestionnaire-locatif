<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EcheanceUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();

        if($file->guessExtension() === 'pdf')
        {
            try {
                $base = $this->getTargetDirectory();
                $dirs = explode("_",$fileName);
                $annee =$dirs[0];
                $mois =$dirs[1];
                $userId =  substr($dirs[2],0,-4);

                //CREATION DES DOSSIER SI INEXISTANTS (ex: ~ 2019/01/12345)
                $dir = $base.'/'.$userId.'/'.$annee.'/'.$mois;
                $file->move($dir, $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        }elseif ($file->guessExtension() === 'zip'){
            $zip = new \ZipArchive();
            $zip->open($file);
            $zip->extractTo($this->getTargetDirectory());
            $zip->close();
            foreach (scandir($this->getTargetDirectory()) as $fileName) {
                $base = $this->getTargetDirectory();
                $dirs= explode("_",$fileName);
                $annee =$dirs[0];
                $mois =$dirs[1];
               if (stripos($fileName,'PDF') !== false && !stripos($fileName,'_P_') !== false) {
                   $userId =  substr($dirs[2],0,-4);
                   $this->checkDir($base,$userId,$annee,$mois);
                   rename($base.'/'.$fileName,$base.'/'.$userId.'/'.$annee.'/'.$mois.'/'.$fileName);
               } elseif (stripos($fileName,'_P_') !== false) {
                   $userId =  substr($dirs[3],0,-4);
                   $this->checkDir($base,$userId,$annee,$mois);
                   if(!is_dir($base.'/'.$userId.'/'.$annee.'/'.$mois.'/parking')) {
                       mkdir($base.'/'.$userId.'/'.$annee.'/'.$mois.'/parking');
                   }
                   rename($base.'/'.$fileName,$base.'/'.$userId.'/'.$annee.'/'.$mois.'/parking/'.$fileName);
               }
            }
        } else {
            echo '<script>alert("Fichier au format PDF uniquement !")</script>';
        }

        return $fileName;
    }
    public function setTargetDirectory($target)
    {
        $this->targetDirectory = $target;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function checkDir($base,$userId,$annee,$mois) {
        if(!is_dir($base.'/'.$userId)) {
            mkdir($base.'/'.$userId);
        }
        if(!is_dir($base.'/'.$userId.'/'.$annee)) {
            mkdir($base.'/'.$userId.'/'.$annee);
        }
        if(!is_dir($base.'/'.$userId.'/'.$annee.'/'.$mois)) {
            mkdir($base.'/'.$userId.'/'.$annee.'/'.$mois);
        }
    }
}