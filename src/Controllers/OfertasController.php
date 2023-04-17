<?php
namespace App\Controllers;

use App\Config\ResponseHttp;
use App\Config\UploadDoc;
use App\Models\ArchivosOfertasModel;
use App\Models\OfertaModel;
class OfertasController extends BaseController {
   //Guardar una oferta o evento
    final public  function save(string $endPoint){
        if($this->getMethod() == 'post' and  $endPoint == $this->getRoute()){
            if(empty($this->getParam()['objeto']) || empty($this->getParam()['moneda']) ||
               empty($this->getParam()['presupuesto']) || empty($this->getParam()['actividad']) ){
                echo json_encode (ResponseHttp::status400('Todos los campos son requeridos'));

            }elseif(!preg_match(self::$validate_alpha_numerico, $this->getParam()['objeto'])){
                echo json_encode (ResponseHttp::status400('El campo objeto solo admite caracteres alphanumericos'));
            }
            elseif(!preg_match(self::$validate_number,  $this->getParam()['moneda'])){
                echo json_encode (ResponseHttp::status400('El campo moneda solo admite valores enteros'));
            }
            elseif(!preg_match(self::$validate_number,  $this->getParam()['actividad'])){
                echo json_encode (ResponseHttp::status400('El campo actividad solo admite valores enteros'));
            }
            elseif(!preg_match(self::$validate_number, $this->getParam()['presupuesto'])){
                echo json_encode (ResponseHttp::status400('El campo presupuesto solo admite numeros'));
            }else{

                new OfertaModel($this->getParam());
                echo json_encode(OfertaModel::save());
                
            }

            exit;
        }

    }

    //Controlador para subir un archivo asociada a una oferta
    final public  function uploadImg(string $endPoint){
        if($this->getMethod() == 'post' and  $endPoint == $this->getRoute()){
            if(empty($this->getParam()['oferta']) || empty($this->getParam()['titulo']) || empty($_FILES['archivo'])){
             echo json_encode (ResponseHttp::status400('El archivo y el titulo son requeridos'));
         }
         elseif(!preg_match(self::$validate_number,  $this->getParam()['oferta'])){
            echo json_encode (ResponseHttp::status400('El campo oferta solo admite valores enteros'));
         } 
         elseif(!preg_match(self::$validate_alpha_numerico,  $this->getParam()['titulo'])){
            echo json_encode (ResponseHttp::status400('El campo titulo solo admite valores alphanumericos'));
         }
         else{
            $upload =UploadDoc::uploadImage($_FILES['archivo']);
            new ArchivosOfertasModel($this->getParam());
            echo json_encode(ArchivosOfertasModel::saveDocument($upload));
         }
            exit;
        }

    }

    //controlador para el filtro de eventos u ofertas
     public  function SearchOfertas(string $endPoint){
        if($this->getMethod() == 'post' and  $endPoint == $this->getRoute()){
            // if((empty($this->getParam()['estado']) or $this->getParam()['estado'] =='') and empty($this->getParam()['descripcion']) and empty($this->getParam()['objeto']) ){
            //     echo json_encode (ResponseHttp::status400('Se debe enviar por lo menso un parametro para filtrar'));
            // }
            if(!empty($this->getParam()['estado']) and !preg_match(self::$validate_number, $this->getParam()['estado'])){
             echo json_encode (ResponseHttp::status400('el campo estado  solo admite valores enteros y se debe envair por lo menos un parametro para el filtro'));
           }else{

                $objeto =$this->getParam()['objeto'];
                $descripcion =$this->getParam()['descripcion'];
                $id_estado =$this->getParam()['estado'];
            
            echo json_encode(OfertaModel::SearchOferta($objeto,$descripcion,$id_estado));
         }
            exit;
        }

    }

    public  function GetCoins(string $endPoint){
        if($this->getMethod() == 'get' and  $endPoint == $this->getRoute()){
            echo json_encode(OfertaModel::GetCoins());
            exit;            
        }
    }

    public  function GetEstado(string $endPoint){
        if($this->getMethod() == 'get' and  $endPoint == $this->getRoute()){
            echo json_encode(OfertaModel::AllEstado());
            exit;            
        }
    }

    public  function GetActivity(string $endPoint){
        if($this->getMethod() == 'post' and  $endPoint == $this->getRoute()){
            $activity =$this->getParam()['actividad'];
            if (!isset($activity)) {
                echo json_encode(ResponseHttp::status400('El campo actividad debe tener por lo menos algun caracter'));
                exit;    
            }
            echo json_encode(OfertaModel::GetActivity($activity));
            exit;            
        }

    }
}