<?php

namespace database;

use PDO;
use PDOException;

class Database{

    private $connection;
    private $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
    private $dbHost = DB_HOST;
    private $dbUserName = DB_USERNAME;
    private $dbName = DB_NAME;
    private $dbPassword = DB_PASSWORD;

    function __construct()
    {
        try
        {
            $this->connection = new PDO("mysql:host=" . $this->dbHost. ";dbname=" . $this->dbName, $this->dbUserName, $this->dbPassword, $this->options);
            echo 'ok';
        }
        catch(PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }

}