<?php

class User {
    //public $id, $gender, $firstname, $lastname, $username, $password, $email, $avatar;
    public $userData;

    public static function constructNewUser($gender, $firstname, $lastname, $username, $password, $email, $avatar) {
        $recData = array("gender"=>$gender, "firstname"=>$firstname, "lastname"=>$lastname,
        "username"=>$username, "password"=>$password, "email"=>$email, "avatar"=>$avatar);

        return new User($recData);
    }

    public function __construct($recData)
    {
        $this->userData = $recData;
    }

    public function getId()
    {
        return isset($this->userData["id"]) ? $this->userData["id"] : NULL;
    }

    public function getGender() 
    {
        return isset($this->userData["gender"]) ? $this->userData["gender"] : NULL;
    }

    public function getFirstname() 
    {
        return isset($this->userData["firstname"]) ? $this->userData["firstname"] : NULL;
    }

    public function getLastname() 
    {
        return isset($this->userData["lastname"]) ? $this->userData["lastname"] : NULL;
    }

    public function getUsername() 
    {
        return isset($this->userData["username"]) ? $this->userData["username"] : NULL;
    }

    public function getPassword() 
    {
        return isset($this->userData["password"]) ? $this->userData["password"] : NULL;
    }

    public function getEmail() 
    {
        return isset($this->userData["email"]) ? $this->userData["email"] : NULL;
    }

    public function getAvatar() 
    {
        return isset($this->userData["avatar"]) ? $this->userData["avatar"] : NULL;
    }

}

?>