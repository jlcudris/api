<?php

   use App\Config\ErrorLog;
   use App\Config\ResponseHttp;

   require dirname(__DIR__) . '/vendor/autoload.php';
   define ('SITE_ROOT', realpath(dirname(__FILE__)));


//ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);//CORS Desarrollo

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,PATCH,DELETE');
header("Allow: POST, GET, OPTIONS, PUT, PATCH , DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization,Access-Control-Allow-Headers,ip"); 
header('content-type: application/json; charset=utf-8');

ErrorLog::activateErrorLog();

//validacion de rutas
if (isset($_GET['route'])) {
    
    $url = explode('/',$_GET['route']);
    //rutas existentes
    $lista=['ofertas','ofertas/documentos','ofertas/filtros/','cronograma','ofertas/coins/',"ofertas/activity/","ofertas/estado/"];
    $file = dirname(__DIR__) . '/src/Routes/' .$url[0]. '.php';

    if (!in_array($url[0],$lista)) {
        echo json_encode(ResponseHttp::status400());         
        exit;
    }      
    
    if (is_readable($file)) {
        require $file;
        exit;
    } else {
        echo json_encode(ResponseHttp::status400());  
    }

} else {
    echo json_encode(ResponseHttp::status404());
    exit;
}