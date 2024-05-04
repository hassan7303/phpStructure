<?php

namespace database;

use PDO;
use PDOException;

class Database
{
    private $prefix = 10;
    private $connection;
    private $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
    private $dbHost = DB_HOST;
    private $dbUserName = DB_USERNAME;
    private $dbName = DB_NAME;
    private $dbPassword = DB_PASSWORD;

    function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUserName, $this->dbPassword, $this->options);
        } catch (PDOException $e) {
            error_log(
                "errorMessage" . $e->getMessage() . "line" . self::$prefix . __LINE__,
                3,
                "../storage/logs/error.log"
            );
            echo "اتصال به دیتا بیس با خطا مواجه شد";
            exit;

        }
    }
    public function select($sql, $values = null)
    {
        try {
            $stmt = $this->connection->prepare($sql);
            if ($values === null) {
                $stmt->execute();
            } else {
                $stmt->execute($values);
            }
            $result = $stmt;
            return [
                "status" => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            error_log(
                "errorMessage" . $e->getMessage() . "line" . self::$prefix . __LINE__,
                3,
                "../storage/logs/error.log"
            );
            return [
                "status" => false,
                'snackbar' => [
                    'type' => "error",
                    'message' => "اتصال به دیتا بیس با خطا مواجه شد",
                ],
            ];
        }

    }
    //insert("users",['name','age'],['hassan','23']);
    public function insert($tableName,$fields,$values){
        try {
            $stmt = $this->connection->prepare("INSERT INTO".$tableName."(".implode(', ', $fields)." , created_at) VALUES ( :" . implode(', :', $fields) . " , now() );");
            $stmt->execute(array_combine($fields, $values));
            return true;
        } catch (PDOException $e) {
            error_log(
                "errorMessage" . $e->getMessage() . "line" . self::$prefix . __LINE__,
                3,
                "../storage/logs/error.log"
            );
            return [
                "status" => false,
                'snackbar' => [
                    'type' => "error",
                    'message' => "اتصال به دیتا بیس با خطا مواجه شد",
                ],
            ];
        }

    }

}