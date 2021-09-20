<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model
{
    public static function getAll(){
        // $host = 'localhost';
        // $dbname = 'mvc';
        // $username = 'root';
        // $password = '';

        try {
            // $db = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8",$username, $password);
            $db = static::getDB();
            $stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
            //potrzeba \ przed PDOException ponieważ używamy namespace. Dzieki temu PHP używa global namespace.
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
