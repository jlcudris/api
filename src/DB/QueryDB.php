<?php

namespace App\DB;

use App\Config\ResponseHttp;

class QueryDB extends conexionDB {

    public static function exist(string $request, string $condicion, $param)
    {
        try {
            $con=self::getConnection();
            $query =$con->prepare($request);
            $query->execute([
                $condicion=>$param
            ]);

            $res =($query->rowCount() ==0) ? false : true;
            return $res;

        }catch(\PDOException $e){
            error_log('QueryDB::exist ->' .$e);
            die(json_encode(ResponseHttp::status500()));

        }
    }

    public static function getAllModel(string $model)
    {
        try {
            $sql = "SELECT * from {$model}";
            $con=self::getConnection();
            $query =$con->prepare($sql);
            $query->execute();
            return $query->fetchAll(\PDO::FETCH_ASSOC);;

        }catch(\PDOException $e){
            error_log('QueryDB::getAllModel ->' .$e);
            die(json_encode(ResponseHttp::status500()));

        }
    }


}