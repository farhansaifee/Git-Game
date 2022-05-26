<?php

require_once "utility/ErrorCodes.class.php";

require_once "utility/DB.class.php";

$db = new DB();

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
