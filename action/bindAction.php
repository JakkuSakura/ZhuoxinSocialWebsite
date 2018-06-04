<?php
require_once "configure.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/tools.php";
require_once ROOT . "/User.php";
if (!isset($_POST['strEmail']))
    sendmsg("failed", "邮箱不能为空");

$email = $_POST['strEmail'];
$user = User::getUser("email", $email);
if (!$user->valid())
    sendmsg("failed", "用户邮箱不存在");


if (!isset($_POST['passPassword']))
    sendmsg("failed", "密码为空");
$password = $_POST['passPassword'];
if ($password != $user->obj->password) {
    sendmsg("failed", "密码错误！");
}

if (!isset($_SESSION['openid']))
    sendmsg("failed", "您没有用qq登录");
$ip = getIP();
if ($ip == "::1")
    $ip = "127.0.0.1";
Database::update("user", "bind_openid", $_SESSION['openid'], "id=" . $user->id);
$noautologin = true;
require_once "loginAction.php";
login($email, $password, false);
sendmsg("successful", "绑定完成", "json");
