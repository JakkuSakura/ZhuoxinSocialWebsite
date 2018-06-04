<?php
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/26
 * Time: 1:04
 */
require_once 'User.php';
require_once 'Database.php';
$user = User::getUser("email", $_GET['email']);
$to = $_GET['email'];

if ($_GET['code'] == md5("This is salt" . $to) . md5(base64_encode($to)))
{
    Database::update("user", "verified", "1" ,"id=" . $user->id);
    echo "验证成功了";
}
else
{
    echo "验证出错了";
}