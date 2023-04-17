<?php

namespace App\Models;

use App\Config\ResponseHttp;
use App\DB\conexionDB;
use App\DB\QueryDB;

class CronogramaModel extends conexionDB{

    private static  $id_oferta;
    private static  $fecha_inicio;
    private static  $fecha_cierre;
    private static  $created_at;
    

    public function __construct(array $data)
    {
        self::$id_oferta =$data['oferta'];
        self::$fecha_inicio =$data['fecha_inicio'];
        self::$fecha_cierre =$data['fecha_cierre'];

    }

    //METHOD GET cronograma
    final public static function getOferta(){return self::$id_oferta;}
    final public static function getFechaInicio(){return self::$fecha_inicio;}
    final public static function getFechaCierre(){return self::$fecha_cierre;}
    final public static function getCreateAd(){return self::$created_at;}
 

    //METHOD SET 
    final public static function setOferta(int $oferta){self::$id_oferta =$oferta;}
    final public static function setFechaInicio($fecha_inicio){self::$fecha_inicio =$fecha_inicio;}
    final public static function setFechaCierre($fecha_cierre){self::$fecha_cierre =$fecha_cierre;}
    final public static function setCreateAd($created_at){self::$created_at=$created_at;}

      //Crer Cronograma
      final public static function save()
      {
          if (!QueryDB::exist("SELECT id FROM ofertas WHERE id = :id",":id",self::getOferta())){
              return ResponseHttp::status400('La Oferta o Evento no existe');
          }

          self::setCreateAd(date("Y-m-d H:i:s"));
       
          try{
              $con =self::getConnection();
              $query1="INSERT INTO cronograma (id_oferta,fecha_inicio,fecha_cierre,created_at) VALUES";
              $values ="(:id_oferta, :fecha_inicio, :fecha_cierre,:created_at)";
              $query = $con->prepare($query1 . $values);
              $query->execute([
                  ':id_oferta' =>self::getOferta(),
                  ':fecha_inicio' => self::getFechaInicio(),
                  ':fecha_cierre' => self::getFechaCierre(),
                  ':created_at' => self::getCreateAd()
              ]);
  
              if($query->rowCount() > 0){
                //cambio de estado para la oferta
                QueryDB::exist("UPDATE ofertas  SET id_estado = 4 WHERE id =:id",":id",self::getOferta());
                  return ResponseHttp::status200('Cronograma creado exitosamente');
              }else{
                  return ResponseHttp::status400('No se pudo crear el Cronograma');
              }
  
          }catch(\PDOException $e){
              error_log('CronogramaModel::post ->' . $e);
              die(json_encode(ResponseHttp::status400()));
          }
  
      }
  

}