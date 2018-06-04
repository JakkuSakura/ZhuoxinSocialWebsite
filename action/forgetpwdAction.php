<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/14
 * Time: 14:29
 */
require_once dirname(__FILE__) . '/../tools.php';
require_once dirname(__FILE__) . '/../Database.php';
require_once dirname(__FILE__) . '/../User.php';
require_once dirname(__FILE__) . '/../sendEmail.php';
if (!isset($_POST['strEmail']))
{
    sendmsg("failed","不要直接访问这个界面");
}
$email = $_POST['strEmail'];
$user = User::getUser("email", addslashes($email));
if (!$user->valid())
{
    sendmsg("failed", "邮箱不存在");
}
$key = md5("This is salt2" . rand(0, 66666666));
Database::update('user', 'reset_key', $key, "id={$user->id}");
sendEmail($email,"【卓信】找回密码", "您好，你在卓信申请了找回密码，请点击<a href='http://{$_SERVER['SERVER_NAME']}/resetpswd.php?email={$email}&code={$key}'>http://{$_SERVER['SERVER_NAME']}/resetpswd.php?email={$email}&code={$key}</a><br> 如非本人操作，请无视此邮件");
sendmsg("successful", "已经向您的邮箱发送邮件");