<?php

namespace App\Models;

use App\Config\ResponseHttp;
use App\DB\conexionDB;
use App\DB\QueryDB;

class OfertaModel extends conexionDB{

    private static  $objeto;
    private static  $descripcion;
    private static  $id_moneda;
    private static  $presupuesto;
    private static  $id_actividad;
    private static  $id_estado;
    private static  $created_at;
    private static  $updated_at;


    public function __construct(array $data)
    {
        self::$objeto =$data['objeto'];
        self::$descripcion =$data['descripcion'];
        self::$id_moneda =$data['moneda'];
        self::$presupuesto =$data['presupuesto'];
        self::$id_actividad =$data['actividad'];
        // self::$id_estado =$data['estado'];

    }

    //METHOD GET OFERTAS
    final public static function getObjeto(){return self::$objeto;}
    final public static function getDescripcion(){return self::$descripcion;}
    final public static function getMoneda(){return self::$id_moneda;}
    final public static function getPresupuesto(){return self::$presupuesto;}
    final public static function getActividad(){return self::$id_actividad;}
    final public static function getEstado(){return self::$id_estado;}
    final public static function getCreate(){return self::$created_at;}
    final public static function getUpdate(){return self::$updated_at;}

    //METHOD SET 
    final public static function setObjeto(string $objeto){self::$objeto =$objeto;}
    final public static function setDescripcion(string $descripcion){self::$descripcion = $descripcion;}
    final public static function setMondeda(int $moneda){self::$id_moneda =$moneda;}
    final public static function setPresupuesto($presupuesto){self::$presupuesto = $presupuesto;}
    final public static function setActividad(int $actividad){self::$id_actividad =$actividad;}
    final public static function setEstado(int $estado){self::$id_estado = $estado;}
    final public static function setCreate($created_at){self::$created_at=$created_at;}
    final public static function setUpdate($updated_at){self::$updated_at =$updated_at;}

    //Crer Oferta
    final public static function save()
    {
    
        if (!QueryDB::exist("SELECT id FROM moneda WHERE id = :id",":id",self::getMoneda())){
            return ResponseHttp::status400('La moneda no existe');
        }
        if (!QueryDB::exist("SELECT id FROM producto WHERE id =:id",":id",self::getActividad())){
            return ResponseHttp::status400('La Actividad no existe');
        }

        self::setEstado(1);
        self::setCreate(date("Y-m-d H:i:s"));
        self::setUpdate(date("Y-m-d H:i:s"));

        try{
            $con =self::getConnection();
            $query1="INSERT INTO ofertas (objeto,descripcion,id_moneda,presupuesto,id_actividad,id_estado,created_at,updated_at) VALUES";
            $values ="(:objeto, :descripcion, :id_moneda, :presupuesto, :id_actividad, :id_estado, :created_at, :updated_at)";
            $query = $con->prepare($query1 . $values);
            $query->execute([
                ':objeto' =>self::getObjeto(),
                ':descripcion' => self::getDescripcion(),
                ':id_moneda' => self::getMoneda(),
                ':presupuesto' => self::getPresupuesto(),
                ':id_actividad' => self::getActividad(),
                ':id_estado' => self::getEstado(),
                ':created_at' => self::getCreate(),
                ':updated_at' => self::getUpdate()
            ]);

            if($query->rowCount() > 0){
                return ResponseHttp::status200('Oferta creada exitosamente');
            }else{
                return ResponseHttp::status400('No se pudo registrar la oferta');
            }

        }catch(\PDOException $e){
            error_log('OfertaModel::post ->' . $e);
            die(json_encode(ResponseHttp::status400()));
        }

    }

    final public static function SearchOferta($objeto,$descripcion,$id_estado)
    {
        $arr = array();
        $sql = "SELECT o.id,o.objeto,o.descripcion,o.id_moneda,o.presupuesto,o.id_actividad,o.id_estado,o.created_at,m.siglas as moneda,p.nombre as nombre_producto,eo.nombre as estado  FROM `ofertas` as o INNER join moneda as m on o.`id_moneda` = m.id INNER JOIN producto as p on o.`id_actividad` = p.id INNER JOIN estado_oferta as eo on o.`id_estado` =eo.id  where 1 ";
      
        
        if($objeto !=""){
            $sql .= " and o.objeto like  ?";
            $arr[] = "%$objeto%";
        }
        
        if($descripcion!=""){
            $sql .= " and o.descripcion like  ?";
            $arr[] = "%$descripcion%";
        }
        
        if($id_estado!=""){
            $sql .= " and o.id_estado =  ?";
            $arr[] = $id_estado;
        }

        try{
            $con =self::getConnection();
            $query = $con->prepare($sql);
            $query->execute($arr);
            $rs = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $rs;
         
        }catch(\PDOException $e){
            error_log('OfertaModel::SearchOferta ->' . $e);
            die(json_encode(ResponseHttp::status400()));
        }
        

    }

    final public static function GetCoins()
    {
        $model = filter_var('moneda', FILTER_SANITIZE_STRING);
        $selectAll =QueryDB::getAllModel($model);
        return $selectAll;
    }

    final public static function GetActivity($activity)
    {   
        $sql ="SELECT * FROM producto where nombre like ?";
        $con =self::getConnection();
        $query = $con->prepare($sql);
        $query->execute([
            "%$activity%"
        ]);
        $rs = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $rs;
    }

    final public static function AllEstado()
    {   
        $model = filter_var('estado_oferta', FILTER_SANITIZE_STRING);
        $selectAll =QueryDB::getAllModel($model);
        return $selectAll;
    }

    

}
