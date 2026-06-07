<?php

class Database {

    private static ?PDO $connection = null;

    private string $host = 'localhost';

    private string $dbname = 'seoudi_market';

    private string $username = 'root';

    private string $password = '';



    private function __construct() {}



    public static function getConnection(): PDO {

        if(self::$connection === null){

            $database =
                new self();

            self::$connection =
                $database->connect();
        }

        return self::$connection;
    }



    private function connect(): PDO {

        try {

            $pdo = new PDO(

                "mysql:host={$this->host};
                dbname={$this->dbname};
                charset=utf8mb4",

                $this->username,

                $this->password

            );

            $pdo->setAttribute(

                PDO::ATTR_ERRMODE,

                PDO::ERRMODE_EXCEPTION

            );

            $pdo->setAttribute(

                PDO::ATTR_DEFAULT_FETCH_MODE,

                PDO::FETCH_ASSOC

            );

            return $pdo;

        } catch (PDOException $e) {

            die(

                "Database connection failed: "
                . $e->getMessage()

            );
        }
    }
}