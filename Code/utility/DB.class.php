<?php

require_once 'config/connectDB.php';
require_once 'utility/PW.class.php';

require_once "utility/DateTimeExt.class.php";
require_once "utility/ErrorCodes.class.php";

class DB
{
    public function __construct($mail)
    {
        $connection = new connectDB();
        $this->conn = $connection->connect();
        $this->mail = $mail;
    }

    public function createUserChallenge($user_id){
        $stmt = $this->conn->prepare("INSERT INTO user_challenge (user_id,challenge_id,last_done_task) VALUES (:user_id,1,0)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public function registerUser($user)
    {
        $gender = trim($user->getGender());
        $firstname = trim($user->getFirstname());
        $lastname = trim($user->getLastname());
        $username = trim($user->getUsername());
        $password = trim($user->getPassword());
        $email = trim($user->getEmail());

        if ($gender == NULL || $username == NULL || $email == NULL || $password == NULL) {
            return -1;
        }

        if ($this->usernameAvaible($username) == false) {
            return -2;
        }

        if ($this->emailAvaible($email) == false) {
            return -3;
        }

        $hashedPW = hash('sha256', $password);

        $stmt = $this->conn->prepare("INSERT INTO user (Gender, Firstname, Lastname, Username, Email, Password) VALUES (:gender, :firstname, :lastname, :username, :email, :password )");
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPW);
        $stmt->execute();
        $this->createUserChallenge($this->conn->lastInsertId());
        return 0;
    }




    public function loginUser($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE Username=:username AND Password=:password");

        $hashedPW = hash('sha256', $password);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPW);

        $stmt->execute();
        $id = (int)$stmt->fetchColumn();

        $result = array("token"=>null,"id"=>$id);

        if ($id == false) {
            $result["id"] = -1;
            return $result;
        }

        $token = PW::generate();
        $stmt = $this->conn->prepare("UPDATE user SET Token=:token WHERE UserID=:id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result["token"] = $token;
        return $result;
    }

    public function logoutUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE user SET Token=null WHERE UserID=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return 0;
    }

    public function getUser($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE UserID=:id");

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) return null;

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = User::constructNewUser(
            $result["Gender"],
            $result["Firstname"],
            $result["Lastname"],
            $result["Username"],
            $result["Password"],
            $result["Email"],
            $result["Avatar"]
        );

        return $user;
    }

    public function getAllChallenges(){
        $data = array();

        $stmt = $this->conn->prepare("SELECT * FROM challenge");

        $stmt->execute();

        if ($stmt->rowCount() == 0) return $data = "NULL";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        return $data;

    }

    public function getAllTasksForChallenge($id)
    {
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM task WHERE challenge_id=:id");

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) return $data = "NULL";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        return $data;
    }

    public function getChallengeIdForUser($id)
    {
        $data = array();
        $stmt = $this->conn->prepare("SELECT challenge_id FROM user_challenge WHERE user_id=:id");

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) return $data = "NULL";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        return $data;
    }

    public function getUserLeftTasks($user_id,$challenge_id)
    {
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM user_challenge WHERE challenge_id=:challenge_id AND user_id=:user_id");

        $stmt->bindParam(':challenge_id', $challenge_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() == 0) return $data = "NULL";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        return $data;
    }


    public function deleteUser($userId)
    {
    }

    private function usernameAvaible($username)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE Username=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id == false) {
            return true;
        } else {
            return false;
        }
    }

    private function emailAvaible($email)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE Email=:email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id == false) {
            return true;
        } else {
            return false;
        }
    }

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE Email=:email");
        $stmt->bind_param(':email', $email);
        if (!$stmt->execute()) {
            return -1;
        }
        $id = $stmt->fetchColumn();
        if ($id == false) {
            return -2;
        } else {
            return $id;
        }
    }

    public function editUserChallenge($current_task,$user_id,$challenge_id){
        $stmt = $this->conn->prepare("UPDATE user_challenge SET last_done_task=:last_done_task WHERE user_id=:user_id AND challenge_id=:challenge_id");
        $stmt->bindParam(':last_done_task', $current_task);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':challenge_id', $challenge_id);
        if ($stmt->execute()) {
            return 0;
        } else {
            return -4;
        }
    }

    public function editUserChallengePassed($user_id,$challenge_id){
        $stmt = $this->conn->prepare("UPDATE user_challenge SET last_done_task=0, challenge_id=:challenge_id WHERE user_id=:user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':challenge_id', $challenge_id);
        if ($stmt->execute()) {
            return 0;
        } else {
            return -4;
        }
    }

    public function editUser($user)
    {

        $oldUser = $this->getUser($_SESSION["user"]["ID"]);
        if ($oldUser == null) return -1;

        if($user->getGender() == null) $gender = $oldUser->userData["gender"]; else $gender = trim($user->getGender());
        if($user->getFirstname() == null) $firstname = $oldUser->userData["firstname"]; else $firstname = trim($user->getFirstname());
        if($user->getLastname() == null) $lastname = $oldUser->userData["lastname"]; else $lastname = trim($user->getLastname());
        if($user->getUsername() == null) $username = $oldUser->userData["username"]; else $username = trim($user->getUsername());
        $password = trim($user->getPassword());
        if($user->getEmail() == null) $email = $oldUser->userData["email"]; else $email = trim($user->getEmail());
        $id = $user->getId();

        $stmt = $this->conn->prepare("UPDATE user SET  Gender=:gender, Firstname=:firstname, Lastname=:lastname, Username=:username, Email=:email, Password=:password WHERE UserId=:id");
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);


        if ($password == null) {
            $password = $oldUser->userData["password"];
        } else {
            $password = hash('sha256', $password);
        }

         if ($username!=$oldUser->userData["username"] && !$this->usernameAvaible($username)) {
          return -2;
        }

        if ($email != $oldUser->userData["email"] && !$this->emailAvaible($email)) {
            return -3;
        }

        if ($stmt->execute()) {
            return 0;
        } else {
            return -4;
        }
    }

    public function verifyUser($userId, $token)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE UserID=:userid AND Token=:token");
        $stmt->bindParam(':userid', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $id = $stmt->fetchColumn();
        if ($id == false) {
            return false;
        } else {
            return true;
        }
    }

    public function editAvatar($name, $id){

        $stmt = $this->conn->prepare("UPDATE user SET Avatar=:avatar WHERE UserId=:id");
        $stmt->bindParam(':avatar', $name);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return 0;
        } else {
            return -4;
        }
    }

    public function changePassword($email){

        $stmt = $this->conn->prepare("SELECT * FROM user WHERE Email = :email"); //Username überprüfen
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count != 0){
            $token = PW::generate();
            $stmt = $this->conn->prepare("UPDATE user SET Token2=:token WHERE Email=:email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            //Add recipient
            $this->mail->addAddress($email);
            $bodyContent = '<h1>Reset password</h1>';
            $bodyContent = '<br><p>Please enter the following token to reset password</p><p>';
            $bodyContent .= $token;
            $bodyContent.= '</p>';
            $this->mail->Body = $bodyContent;
            if(!$this->mail->send()){
                return "Mail NOT sent".$this->mail->ErrorInfo;
            }else{
                $this->mail->clearAllRecipients();
                return "Mail sent";
            }
        } else {
            return "Diese Email ist nicht angemeldet";
        }
    } 

    public function loginUserWithToken($token2, $password)
    {
        $stmt = $this->conn->prepare("SELECT UserID FROM user WHERE Token2 =:token2");

        $stmt->bindParam(':token2', $token2);
        
        $stmt->execute();
        $id = (int)$stmt->fetchColumn();

        $result = array("token"=>null,"id"=>$id);

        if ($id == false) {
            $result["id"] = -1;
            return $result;
        }

        $hashedPW = hash('sha256', $password);
        $token = PW::generate();
        $stmt = $this->conn->prepare("UPDATE user SET Token=:token, Password=:password WHERE UserID=:id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':password',$hashedPW);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result["token"] = $token;
        return $result;
    }
}
