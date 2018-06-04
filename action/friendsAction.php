<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/26
 * Time: 18:49
 */
require_once ROOT . "/User.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/tools.php";
require_once ROOT . "/sendEmail.php";
if (!isset($_POST['optype']))
    sendmsg("failed", "请不要直接访问这个界面");
$optype = $_POST['optype'];
if (!User::isLoggedIn())
    sendmsg("failed", "请在操作前登录");
if (User::getLoginUser()->banned)
    sendmsg("failed", "你已被封禁无法操作");
$user = User::getLoginUser();
if (!isset($_POST['user1id']))
    sendmsg("failed", "缺参数user1id");
$user1id = (int)$_POST['user1id'];


switch ($optype) {
    case "apply":
        if ($user->isFriend($user1id))
            sendmsg("failed", "您们已经是好友了或好友申请已经发送");
        $user1 = User::getUser('id', $_POST['user1id']);

        if ($user1->id == $user->id)
            sendmsg("failed", "你不能添加自己为好友");

        $sql = "INSERT INTO `friendship` (`user1id`, `user2id`, `state`, `applicate_date`, `accept_date`) VALUES ({$user1->id}, {$user->id}, '0', NOW(), '');";

        Database::SQLquery($sql);
        sendEmail($user1->email, "【卓信】好友请求", "你有新的好友请求了，访问查看");

        sendmsg("successful", "你的好友请求已经发送");
        break;

    case "unfriend":
    case "cancel":
    case "refuse":
        if (!$user->isFriend($user1id)) {
            sendmsg("failed", "你们不是好友关系");
        }
        $slt = $user->getFriendship($user1id);

        Database::SQLquery("DELETE FROM `friendship` WHERE id={$slt->id}");
        sendmsg("successful", "已经解除好友关系");

        break;
    case "accept":
        if (!$user->isFriend($user1id)) {
            sendmsg("failed", "你们不是好友关系");
        }
        $slt = $user->getFriendship($user1id);
        if ($slt->state != 0)
            sendmsg("failed", "你们好友关系不为待通过");
        if ($slt->user1id != $user->id)
            sendmsg("failed", "你不是待申请一方");

        Database::updateItems("friendship", ["state", "accept_date"], ["1", "'+NOW()+'"], "id={$slt->id}");
        sendmsg("successful", "你们已经成为好友");
        break;
    default:
        sendmsg("failed", "未知操作" . $optype);

}
