<?php

namespace Utils;

class DBConnection {

    private static $db;
    private static $conn;


    private function __construct()
     {
        global $config;

        try 
     {
        self::$conn = new \PDO('mysql:host='.$config['db']['db_host'].';dbname='.$config['db']['db_dbname'], $config['db']['db_username'], $config['db']['db_password']);
        self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
     }
     catch(PDOException $e) 
     {
        echo 'ERROR: ' . $e->getMessage();
     }
    
    }


    public static function getInstance() 
    {
        if (!isset(self::$db)) {
            self::$db = new DBConnection();
        }

        return self::$db;
    }

    private function __clone()
    {
       
    }
     private function __wakeup()
    {

    }
    function __destruct() {
        
        self::$conn = null;
    }

    public static function getConnection() {
        return self::$conn;
    }
}

?>