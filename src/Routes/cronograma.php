<?php

use App\Config\ResponseHttp;
use App\Controllers\CronogramaController;
ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);//CORS Desarrollo
$app = new CronogramaController();

$app->saveCronograma('cronograma/');

echo json_encode(ResponseHttp::status404());