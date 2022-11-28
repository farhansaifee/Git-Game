<?php
session_start();

function alert($msg)
{ //alert does not work with header, it wont be saved after refreshing the page
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function popup($msg)
{
    echo "<script type='text/javascript'>popup('$msg');</script>";
}

include_once 'utility/DateTimeExt.class.php';
include_once 'model/User.class.php';
include_once 'utility/DB.class.php';
include_once 'utility/LDAP.class.php';
include_once 'utility/PW.class.php';

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

$menu = isset($_GET["menu"]) ? $_GET["menu"] : NULL;

$db = new DB($mail);
$ldap = new LDAP();
include_once 'utility/preHeader.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>Git Game</title>
    <link rel="icon" href="resources/Logo_Orange.png">
    <!-- CSS -->
    <link rel="stylesheet" href="css/mainstyle.css">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/legalNotice.css">
    <link rel="stylesheet" href="css/help.css">
    <link rel="stylesheet" href="css/terminal.css">
    <link rel="stylesheet" href="css/contact.css">
    <!-- JS -->
    <script src="js/jquery-3.6.0.js"></script>
    <script src="js/jqueryAnimateColors.js"></script>
    <script src="js/mainscript.js"></script>
    <script src="js/utility.js"></script>
    <script src="js/responsiveTerminal.js"></script>
</head>

<body>
    <div class="page-container">
        <div class="content-wrap">

            <?php
            if (isset($popup) && !empty($popup)) popup($popup);
            //DEBUG //shows UserID in top right corner if a user is logged in
            if (isset($_SESSION["user"]["ID"])) {
                $user = $db->getUser($_SESSION["user"]["ID"]);
                echo "<script>var AUTH_TOKEN=\"{$_SESSION['user']['token']}\"; var USERID={$_SESSION['user']['ID']};</script>";
                echo
                "<div class='debug-top-right'>{$user->getUsername()}
                    <i class='bi bi-person-fill'></i>
                    <span id='user_id' style='display:none;'>{$_SESSION["user"]["ID"]}</span> 
                    <form action='index.php' method='post'>
                        <input type='hidden' name='method' value='logout' ><input type='submit' value='Logout' class='btn btn-danger btn-sm'>
                    </form>
                </div>";
            }

            if (isset($menu) && $menu != 'main')
                include 'include/sidenav.php';

            switch ($menu) {
                case 'main':
                    include 'include/main.php';
                    break;
                case 'dashboard':
                    include 'include/dashboard.php';
                    break;
                case 'profile':
                    include 'include/profile.php';
                    break;
                case 'highscore':
                    include 'include/highscore.php';
                    break;
                case 'help':
                    include 'include/help.php';
                    break;
                case 'legalNotice':
                    include 'include/legalNotice.php';
                    break;
                case 'contact':
                    include 'include/contact.php';
                    break;
                case 'searchResult':
                    include 'include/searchResult.php';
                    break;
                default:
                    include 'include/main.php';
                    break;
            }
            ?>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-5"> <a href="index.php?menu=legalNotice">Legal Notice</a></div>
                    <div class="col-5"> <a href="index.php?menu=help">Help</a> </div>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
