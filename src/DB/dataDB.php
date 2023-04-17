<?php
use App\Config\ErrorLog;
use App\Config\ResponseHttp;
use App\DB\ConexionDB;

ErrorLog::activateErrorLog();

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();

/*********Datos de conexión*********/
$data = array(
    'serverDB' => $_ENV['SERVER_DB'],
    'user'     => $_ENV['USER'],
    'password' => $_ENV['PASSWORD'],
    'DB'       => $_ENV['DB'],
    'IP'       => $_ENV['IP'],
    'port'     => $_ENV['PORT']
);

if(empty($data['user']) ||  empty($data['DB']) || empty($data['IP']) || empty($data['port']) ){ 
       error_log('Campos de la DB vacios');
       die(json_encode(ResponseHttp::status500('Campos de la DB vacios')));
} else  {
    $user     = $data['user'];
    $password = $data['password'];
    $db       = $data['DB'];
    $ip       = $data['IP'];
    $port     = $data['port'];
    $host     = 'mysql:host='.$ip.";".'port='.$port.';dbname='.$db;    
    $connection = ConexionDB::from($host , $user , $password);
} 