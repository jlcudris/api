<?php
namespace App\Controllers;


class BaseController {

    protected static $validate_number = '/^[0-9]+$/';
    protected static $validate_text = '/^[a-zA-Z ]+$/';
    protected static $validate_description = '/^[a-zA-Z ]{1,30}$/';
    protected static $validate_alpha_numerico ='/^[A-Za-z0-9\s]+$/';


    /*************Obtener el metodo HTTP************/
    protected function getMethod()
    {
       return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /******Obtener ruta de la petición HTTP********/
    protected function getRoute()
    {
       return $_GET['route'];
    }

    /******Obtener datos enviados por la URL*******/
    protected function getAttribute()
    {
        $route = $this->getRoute();
        $params = explode('/',$route);
        return $params;
    }

    /*************Obtener los Header************/
    protected function getHeader(string $header)
    {
        $ContentType = getallheaders();
        return $ContentType[$header];
    }

    /*******Obtener los parametros enviados por PUT,POST,PATCH,DELETE******/    
    protected function getParam()
    {
        if ($this->getHeader('Content-Type') == 'application/json') {
           $param = json_decode(file_get_contents("php://input"),true);
        } else {
            $param = $_POST;
        }
        return $param;
    }

   protected function validateDate($date)
   {     $format = 'Y-m-d H:i:s';
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
   }
}