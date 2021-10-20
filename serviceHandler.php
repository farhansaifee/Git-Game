<?php

require_once "utility/ErrorCodes.class.php";

require_once "utility/DB.class.php";

$db = new DB();

$method = isset($_POST["method"]) ? $_POST["method"] : null;
$data = isset($_POST["data"]) ? $_POST["data"] : null;

$errorOccurred = false;

catch (Exception $ex) {
    $errorOccurred = true;
    response("POST", 500, null);
    die();
}

if ($result == null) {
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
}

function response($method, $httpStatus, $data)
{
    header('Content-Type: application/json');
    switch ($method) {
        case "POST":
            http_response_code($httpStatus);
            echo (json_encode($data));
            break;
        default:
            http_response_code(405);
            echo "Method not supported yet!";
    }
}
