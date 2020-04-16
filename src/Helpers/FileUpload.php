<?php

namespace MyChat\Helpers;

class FileUpload
{

    private $uploadDir;
    private $errors;

    /**
     * FileUpload constructor.
     * @param $uploadDir
     */
    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * @param string $uploadDir
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $error
     */
    public function setError($errors)
    {
        $this->errors = $errors;
    }


    public function upload($file){
        if(is_array($file)){
        $error = $file['error'];
        if($error==0){
            $fileName = $file['name'];
            $fileSize = $file['size'];
            $fileTmpName = $file['tmp_name'];

            $fileExtensions = ['jpeg','jpg','png'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $uploadPath = $this->uploadDir.basename($fileName);
            if(!in_array($fileExtension, $fileExtensions)) {
                $this->errors[]="file extension error ";
                return false;
            }
                if($fileSize>2000000){
                $this->errors[]= "filesize error";
                return false;
            }
            if(move_uploaded_file($fileTmpName,$uploadPath)){
                return 'http://localhost/Chat/admin/assets/img/uploads/'.$fileName;
            }
        }else{
            $this->errors[]="upload error";
            return false;
        }

    }else{
            return false;
        }

    }
}