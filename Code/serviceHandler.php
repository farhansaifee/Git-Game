<?php

require_once "utility/ErrorCodes.class.php";

require_once "utility/DB.class.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'gitgame2022@gmail.com';
$mail->Password = 'klsgeewmmudulgps';
$mail->SMTPSecure='tls';
$mail->Port = 587;

//Sender info
$mail->From = 'gitgame2022@gmail.com';
$mail->FromName = 'GitGame GitGame';

//Set email format to html
$mail->isHTML(true);

//Mail subject
$mail->Subject = 'Email for reseting password GitGame';

$db = new DB($mail);

$method = isset($_POST["method"]) ? $_POST["method"] : null;
$data = isset($_POST["data"]) ? $_POST["data"] : null;

$errorOccurred = false;

if (isset($_GET["method"])) {

    switch ($_GET["method"]) {
        case "tasks":
            $res = $db->getAllTasksForChallenge($_GET["id"]);
            response("GET",200,$res);
            break;
        case "user_tasks":
            $res = $db->getUserLeftTasks($_GET["user_id"],$_GET["challenge_id"]);
            response("GET",200,$res);
            break;
        case "user_challenge_id":
            $res = $db->getChallengeIdForUser($_GET["user_id"]);
            response("GET",200,$res);
            break;
    }
}
else if($method!=null && $method=="post_user_task"){
    $currentTask = $_POST["currentTask"];
    $user_id = $_POST["user_id"];
    $challenge_id = $_POST["challenge_id"];
    $db->editUserChallenge($currentTask,$user_id,$challenge_id);
    response("POST", 200, json_encode("Success"));
}
else if($method!=null && $method=="challenge_passed"){
    $user_id = $_POST["user_id"];
    $challenge_id = $_POST["challenge_id"];
    $db->editUserChallengePassed($user_id,$challenge_id);
    response("POST", 200, json_encode("Success"));
}
else if($method!=null && $method=="reset_task"){
    $user_id = $_POST["user_id"];
    $challenge_id = $_POST["challenge_id"];
    $db->editUserChallenge(0,$user_id,$challenge_id);
    response("POST", 200, json_encode("Success"));
}
else if($method!=null && $method=="chanege_pass"){
    $email = $_POST["email"];
    $result = $db->changePassword($email);
    response("POST", 200, json_encode($result));
}
else {if ($result == null) {
    response("POST", 400, null);
} else if (is_array($result) && isset($result["code"])) {
    switch ($result["code"]) {
        case ErrorCodes::OK:
            response("POST", 200, $result);
            break;
        case ErrorCodes::NoContent:
            response("POST", 204, $result);
            break;
        case ErrorCodes::Unauthorized:
            response("POST", 401, null);
            break;
        case ErrorCodes::SQLError:
            response("POST", 500, null);
            break;
        default:
            response("POST", 400, null);
    }
} else {
    response("POST", 200, $result);
}}

function response($method, $httpStatus, $data)
{
    header('Content-Type: application/json');
    switch ($method) {
        case "POST":
            http_response_code($httpStatus);
            echo (json_encode($data));
            break;
        case "GET":
            http_response_code($httpStatus);
            echo(json_encode($data));
            break;
        default:
            http_response_code(405);
            echo "Method not supported yet!";
    }
}
