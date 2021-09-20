<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model
{
    protected static function getDB(){
        static $db = null;

        if ($db === null){
            // $host = 'localhost';
            // $dbname = 'mvc';
            // $username = 'root';
            // $password = '';

                //bierzemy wartości z klasy Config z pliku Config.php
                $dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=utf8';
                // $db = new PDO("mysql:host=$host;dbname=$dbname;chaset=utf8", $username, $password);
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
                
                // Throw exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);           
        }
        return $db;
    }
}