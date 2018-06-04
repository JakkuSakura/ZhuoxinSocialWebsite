<?php
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/3
 * Time: 15:43
 */
require_once "header.php";
require_once "Database.php";
$_SESSION['olemail'] = NULL;
$_SESSION['olid'] = NULL;
$_SESSION['online'] = false;
$_SESSION['openid'] = NULL;
session_unset();
session_destroy();
require_once "User.php";

if (isset($_POST['back'])) {
    header("Location: {$_POST['back']}");
    exit;
} else if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    header("Location: /");
    exit;
}
?>
