<?php
namespace App\Controllers;

use App\Config\ResponseHttp;
use App\Models\CronogramaModel;

class CronogramaController extends BaseController {

 
    final public  function saveCronograma(string $endPoint){

      
        if($this->getMethod() == 'post' and  $endPoint == $this->getRoute()){
            $fecha_now = date('Y-m-d H:i:00');

            if (empty($this->getParam()['oferta']) || empty($this->getParam()['fecha_inicio']) || empty($this->getParam()['fecha_cierre'])) {
                echo json_encode(ResponseHttp::status400('Todos los campos son requeridos'));
            } else if(!preg_match(self::$validate_number,$this->getParam()['oferta'])) {
                echo json_encode(ResponseHttp::status400('El campo oferta solo admite enteros'));
            }elseif(!$this->validateDate($this->getParam()['fecha_inicio'])){
                echo json_encode(ResponseHttp::status400('El campo fecha_incio no tiene el formato valido'));

            } elseif(!$this->validateDate($this->getParam()['fecha_cierre'])){
                echo json_encode(ResponseHttp::status400('El campo fecha_cierre no tiene el formato valido Y-m-d H:m:s'));

            }elseif($this->getParam()['fecha_inicio'] <= $fecha_now ){
                echo json_encode(ResponseHttp::status400('La fecha de inicio debe ser mayor a la fecha actual'));

            }elseif($this->getParam()['fecha_cierre'] <= $this->getParam()['fecha_inicio'] ){
                echo json_encode(ResponseHttp::status400('La fecha de cierre del evento  de ser mayor a la fecha de inicio'));

            }else {
                new CronogramaModel($this->getParam());
                echo json_encode(CronogramaModel::save());
            }
           
            exit;
          
        }
      


    }
}