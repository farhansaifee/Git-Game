<?php
class connectDB
{

    private $serverNme = "localhost";
    private $dbName = "gitgame";
    private $dbUser = "root";
    private $dbPassword = "";
    private $conn = false;

    function connect()
    {

        try {
            $this->conn = new PDO(
                "mysql:host=$this->serverNme;dbname=$this->dbName",
                $this->dbUser,
                $this->dbPassword
            );

            return $this->conn;
        } catch (PDOException $e) {

            echo "error" . $e->getMessage();
            alert("error" . $e->getMessage());
        }
    }

    function disconnect()
    {  // you will need this function later to close connection
        $this->conn = NULL;
        return $this->conn;
    }
}
