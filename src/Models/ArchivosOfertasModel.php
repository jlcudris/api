<?php

namespace App\Models;

use App\Config\ResponseHttp;
use App\Config\UploadDoc;
use App\DB\conexionDB;
use App\DB\QueryDB;

class ArchivosOfertasModel extends conexionDB{

    private static  $id_oferta;
    private static  $titulo;
    private static  $descripcion;
    private static  $url_doc;
    private static  $created_at;
    private static  $updated_at;
   
    public function __construct(array $data)
    {
        self::$id_oferta =$data['oferta'];
        self::$titulo =$data["titulo"];
        self::$descripcion =$data["descripcion"];
       
    }

     //METHOD GET 
     final public static function getOferta(){return self::$id_oferta;}
     final public static function getTitulo(){return self::$titulo;}
     final public static function getDescripcion(){return self::$descripcion;}
     final public static function getUrlDoc(){return self::$url_doc;}
     final public static function getCreatedAt(){return self::$created_at;}
     final public static function getUpdateAt(){return self::$updated_at;}
  
 
     //METHOD SET 

     final public static function setOferta(int $oferta){self::$id_oferta =$oferta;}
     final public static function setTitulo(string $titulo){self::$titulo =$titulo;}
     final public static function setDescripcion(string $descripcion){self::$descripcion =$descripcion;}
     final public static function setUrlDoc(string $url){self::$url_doc =$url;}
     final public static function setCreatedAd($created_at){self::$created_at=$created_at;}
     final public static function setUpdatedAd($updated_at){self::$updated_at=$updated_at;}

     final public static function saveDocument($path)
     {   //validar que exista la oferta
         if (!QueryDB::exist("SELECT id FROM ofertas WHERE id = :id",":id",self::getOferta())){
             return ResponseHttp::status400('La Oferta o Evento no existe');
         }

         self::setCreatedAd(date("Y-m-d H:i:s"));
         self::setUpdatedAd(date("Y-m-d H:i:s"));


         try{

             $con =self::getConnection();

             $query1="INSERT INTO documentacion_ofertas (id_ofertas,titulo,descripcion,url_doc,created_at,updated_at) VALUES";
             $values ="(:id_ofertas, :titulo, :descripcion, :url_doc, :created_at, :updated_at)";
             $query = $con->prepare($query1 . $values);
             $query->execute([
                 ':id_ofertas' =>self::getOferta(),
                 ':titulo' => self::getTitulo(),
                 ':descripcion' => self::getDescripcion(),
                 ':url_doc' => $path,
                 ':created_at' => self::getCreatedAt(),
                 ':updated_at' => self::getUpdateAt()
             ]);
 
             if($query->rowCount() > 0){
               
                 return ResponseHttp::status200('Documento cargado exitosamente');
             }else{
                 return ResponseHttp::status400('No se pudo crear el registro del documento');
             }
 
         }catch(\PDOException $e){
             error_log('ArchivosOfertasModel::saveDocument ->' . $e);
             die(json_encode(ResponseHttp::status400()));
         }
 
     }
 
 
}