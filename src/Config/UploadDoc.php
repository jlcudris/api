<?php
namespace App\Config;


class UploadDoc {

    final public static function uploadImage($file)
    {
                $tmp_name = $file["tmp_name"];
                $name = basename(uniqid().$file["name"]);
               if(move_uploaded_file($tmp_name, SITE_ROOT.'/documentos/'.$name)){

                return $path ='/public/documentos/'.$name;

               }else{
                die(json_encode(ResponseHttp::status400('Ocurrio un error al subir el documento')));
               }
            
    }
}