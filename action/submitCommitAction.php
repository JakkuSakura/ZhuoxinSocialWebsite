<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/3/3
 * Time: 10:09
 */
require_once ROOT . "/User.php";
require_once ROOT . "/tools.php";
if (!User::isLoggedIn())
    die("登录后才可以留言");
if (User::getLoginUser()->banned)
    sendmsg("failed", "你已被封禁无法操作");
if (isset($_GET['remove'])) {
    if (!(isset($_POST['cmtid'])))
        die("不要直接访问这个页面");
    $qry = Database::query("commit", '`user1id`=' . User::getLoginUser()->id . ' and `id`=' . (int)$_POST['cmtid']);
    $cmt = mysqli_fetch_object($qry);
    Database::SQLquery('DELETE FROM `commit` WHERE `id`=' . $cmt->id);
    sendmsg("successful", "删除留言成功");
} else {
    if (!(isset($_POST['uid']) && isset($_POST['strNewComment'])))
        die("不要直接访问这个页面");
    $user1 = User::getUser('id', (int)$_POST['uid']);
    if (!$user1->valid())
        die("对方帐号不存在");
    $user2 = User::getLoginUser();
    $qry = "INSERT INTO `commit`(`user1id`, `user2id`, `text`, `date`) VALUES ({$user1->id}, {$user2->id}, '" . urlencode($_POST['strNewComment']) . "', NOW())";
    $ret = Database::SQLquery($qry);
    if ($user1->id != $user2->id) {
        require_once ROOT . "/sendEmail.php";
        sendEmail($user1->email, "【卓信】你收到一条评论，请及时登录查看", "http://{$_SERVER['SERVER_NAME']}/profile.php?uid={$user2->id}");

    }
    sendmsg("successful", "你的留言已经发送");
}

