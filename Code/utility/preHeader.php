<?php

/*
For formular, or link processing, is perfect for actions that call header().
Good for loading or saving content to the database before actually loading
the page content itself.
*/
if (!isset($_SESSION["user"]["ID"]) || empty($_SESSION["user"]["ID"])) {
    $menu = "main";
}

if (isset($_SESSION["user"]["ID"]) && (empty($menu) || $menu == "main")) {
    header("Location: index.php?menu=dashboard");
}

if (isset($_POST["method"])) {

    if ($_POST["method"] == "register") {
        if (
            isset($_POST["gender"]) && isset($_POST["firstname"]) && isset($_POST["lastname"]) &&
            isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) &&
            isset($_POST["passwordConfirm"])
        ) {

            switch ($_POST["gender"]) {
                case "male":
                    $gender = "Male";
                    break;
                case "female":
                    $gender = "Female";
                    break;
                default:
                    $gender = "Other";
                    break;
            }
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordConfirm = $_POST["passwordConfirm"];
            $avatar = null;

            if (
                strlen($gender) > 10 || strlen($firstname) > 50 || strlen($lastname) > 50 ||
                strlen($username) > 50 || strlen($password) > 50 || strlen($email) > 50
            ) {
                //input too long
                $popup = ("One or more inputs are too long!");
            }

            if (strcmp($password, $passwordConfirm) !== 0) {
                //passwords do not match
                $popup = ("Passwords do not match!");
            } else if (!PW::isValidUsername($username) || !PW::isValidPassword($password)) {
                //invalid username or password
                $popup = ("Username or password not valid!");
            } else {
                $addUser = User::constructNewUser($gender, $firstname, $lastname, $username, $password, $email, $avatar);
                $code = $db->registerUser($addUser);
                if ($code == 0) {
                    $popup = ("Registered successfully!");
                } else {
                    $popup = ("DB Problem! Error code: $code");
                }
            }
        }
    } else if ($_POST["method"] == "login") {
        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $username = $_POST["username"];
            $password = $_POST["password"];

            $result = $db->loginUser($username, $password);
            if($result["id"] === -1){
                $ldap->connect("ldap.technikum-wien.at");
                if ($ldap->bind("uid=$username,ou=people,dc=technikum-wien,dc=at", "$password") === 0) {
                    $firstname = $username;
                    $lastname = $username;
                    $username = $username;
                    $email = $username . "@technikum-wien.at";
                    $password = $password;
                    $passwordConfirm = $password;
                    $avatar = null;
                    $gender = "Male";
                    if (
                        strlen($gender) > 10 || strlen($firstname) > 50 || strlen($lastname) > 50 ||
                        strlen($username) > 50 || strlen($password) > 50 || strlen($email) > 50
                    ) {
                        //input too long
                        $popup = ("One or more inputs are too long!");
                    }
        
                    if (strcmp($password, $passwordConfirm) !== 0) {
                        //passwords do not match
                        $popup = ("Passwords do not match!");
                    } else {
                        $addUser = User::constructNewUser($gender, $firstname, $lastname, $username, $password, $email, $avatar);
                        $code = $db->registerUser($addUser);
                        $result = $db->loginUser($username, $password);
                        if ($code == 0) {
                            $_SESSION["user"]["ID"] = $result["id"];
                            $_SESSION["user"]["token"] = $result["token"];
                        //     echo $_SESSION["user"]["token"];
                            header("Location: index.php?menu=dashboard");
                        } else {
                            $popup = ("DB Problem! Error code: $code");
                        }
                    }
                } else {
                    $popup = "FH Account: Username or password are incorrect";
                }

            }else{
                if ($result["id"] >= 0) {
                    $token = PW::generate();
                    $_SESSION["user"]["ID"] = $result["id"];
                    $_SESSION["user"]["token"] = $result["token"];
                    header("Location: index.php?menu=dashboard");
                } else {
                    $popup = "Invalid username and password combination.";
                }
            }
            
        }
    }else if($_POST["method"] == "reset_password"){
        if (isset($_POST["token"]) && isset($_POST["password"])) {
            $password = $_POST["password"];
            $token = $_POST["token"];
            $result = $db->loginUserWithToken($token,$password);
            if ($result["id"] >= 0) {
                $token = PW::generate();
                $_SESSION["user"]["ID"] = $result["id"];
                $_SESSION["user"]["token"] = $result["token"];
                header("Location: index.php?menu=dashboard");
            } else {
                $popup = "Token or password is invalid.";
            }
        }
    } else if ($_POST["method"] == "logout") {
        if (isset($_SESSION["user"])) {
            $db->logoutUser($_SESSION["user"]["ID"]);
            unset($_SESSION["user"]);
        }
    } else if ($_POST["method"] == "editAvatar") {
            $name = basename( $_FILES["avatar"]["name"]);
            $targetFolder = "avatars/";
            $targetFile = $targetFolder . basename($_FILES["avatar"]["name"]);
            $id = $_SESSION["user"]["ID"];
            if(move_uploaded_file($_FILES['avatar']['tmp_name'],$targetFile)){
                //$popup = ("The file ". basename( $_FILES["avatar"]["name"]). " has been uploaded.");
                $code = $db->editAvatar($name, $id);
                if ($code == 0) {
                    $popup = ("Avatar updated successfully!");
                } else {
                    $popup = ("DB Problem! Error code: $code");
                }
             }
    } else if ($_POST["method"] == "edit") {
        if (isset($_POST["oldPassword"])) //&& ($_POST["oldPassword"]) == *password* 
        {
            $curUser = $db->getUser($_SESSION["user"]["ID"]);
            $password = $lastname = $gender = $username = $email = $firstname = $avatar = null;
            if (isset($_POST["gender"])) {
                switch ($_POST["gender"]) {
                    case "male":
                        $gender = "Male";
                        break;
                    case "female":
                        $gender = "Female";
                        break;
                    default:
                        $gender = "Other";
                        break;
                }
            }

            if (isset($_POST["firstname"])) {
                $firstname = $_POST["firstname"];
            } else {
                $firstname =  $curUser->getFirstname();
            }
            if (isset($_POST["lastname"])) {
                $lastname = $_POST["lastname"];
            } else {
                $lastname =  $curUser->getLastname();
            }
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
            }
            if (isset($_POST["password"])) {
                $password = $_POST["password"];
            }
            if (isset($_POST["username"])) {
                $username = $_POST["username"];
            }

            if (
                strlen($gender) > 10 || strlen($firstname) > 50 || strlen($lastname) > 50 ||
                strlen($username) > 50 ||  strlen($password) > 50 || strlen($email) > 50
            ) {
                $popup = ("One or more inputs are too long!");
            } else if (!isset($_POST["password"]) && PW::isValidPassword($password)) {
                // if (/*!PW::isValidUsername($username) ||*/!PW::isValidPassword($password)) {
                $popup = ("Username or password not valid!");
                //}
            } else {
                $editUser = User::constructNewUser($gender, $firstname, $lastname, $username, $password, $email, $avatar);
                $editUser->userData["id"] = $_SESSION["user"]["ID"];
                $code = $db->editUser($editUser);
                if ($code == 0) {
                    $popup = ("Account data updated successfully!");
                } else if ($code == -2) {
                    $popup = ("This username is invalid or already in use!");
                } else if ($code == -3) {
                    $popup = ("This email is already in use!");
                } else {
                    $popup = ("DB Problem! Error code: $code");
                }
            }
        }
        $menu = "profile";
    }
    else if ($_POST["method"] == "changepass") {

        $email = $_POST["emailreset"];
        $db->changePassword($email);

    } 
}