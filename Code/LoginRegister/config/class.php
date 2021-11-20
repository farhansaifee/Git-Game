<?php
require_once 'connectDB.php';


class Learn
{
    public function __construct()
    {
        $connection = new connectDB();
        $this->conn = $connection->connect();
    }

    /* This is an example how you can use the gitgame DB, have fun :)
    public function newUser($username, $email, $password)
    {
        $stmt = $this->conn->prepare("INSERT INTO user (Username, Email, Password) VALUES (:username, :email, :password )");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
    */
}
