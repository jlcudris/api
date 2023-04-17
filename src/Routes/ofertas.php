<?php
use App\Config\ResponseHttp;
use App\Controllers\OfertasController;

ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);//CORS Desarrollo

$app = new OfertasController();

$app->save('ofertas/');
$app->uploadImg('ofertas/documentos/');
$app->SearchOfertas('ofertas/filtros/');
$app->GetCoins('ofertas/coins/');
$app->GetActivity("ofertas/activity/");
$app->GetEstado("ofertas/estado/");

echo json_encode(ResponseHttp::status404());