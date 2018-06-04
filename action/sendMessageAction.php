<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/3/1
 * Time: 21:51
 */
require_once ROOT . "/User.php";
require_once ROOT . "/tools.php";
if (!User::isLoggedIn())
    sendmsg("failed", "你需要登录你的账号");
if (User::getLoginUser()->banned)
    sendmsg("failed", "你已被封禁无法操作");
if (!isset($_POST['messMessage']) or strlen($_POST['messMessage']) < 10)
    sendmsg("failed", "字数太少");
if (!isset($_POST['uid']))
    sendmsg("failed", "不要直接访问");
$user1 = User::getUser('id', $_POST['uid']);
if (!$user1->valid())
    sendmsg("failed", "用户不存在");
$user2 = User::getLoginUser();
$qry = "INSERT INTO `message`(`user1id`, `user2id`, `text`, `date`) VALUES ({$user1->id}, {$user2->id}, '" . urlencode($_POST['messMessage']) . "', NOW())";
$ret = Database::SQLquery($qry);
if ($user1->id != $user2) {
    require_once "../sendEmail.php";
    sendEmail($user1->email, "【卓信】你收到一条消息，请及时登录查看", "http://{$_SERVER['SERVER_NAME']}/myMessages.php");
}
sendmsg("successful" ,"你的消息已发送");