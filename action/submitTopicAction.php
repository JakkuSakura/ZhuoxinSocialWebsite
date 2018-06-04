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
    sendmsg("failed", "登录后才可以留言");
if (User::getLoginUser()->banned)
    sendmsg("failed", "你已被封禁无法操作");
if (isset($_GET['remove'])) {
    if (!(isset($_POST['tpid'])))
        sendmsg("failed", "不要直接访问这个页面");
    $qry = Database::query("topic", '`id`=' . (int)$_POST['tpid']);
    $tp = mysqli_fetch_object($qry);
    if (User::isLoggedIn() && (User::getLoginUser()->is_admin or User::getLoginUser()->id == $tp->poster or User::getLoginUser()->id == $tp->reply_to))
        Database::SQLquery('DELETE FROM `topic` WHERE `id`=' . $tp->id);
    sendmsg("successful", "删除说说成功");
} else {
    if (!isset($_POST['show_at']) or !isset($_POST['strNewTopic']))
        sendmsg("failed", "不要直接访问这个页面");
    //if ($_POST['char_left'])
    $poster = User::getLoginUser();
    if (!isset($_POST['show_at']))
        sendmsg("failed", "页面不存在");
    $ip = getIP();
    if ($ip == "::1")
        $ip = "127.0.0.1";
    if (isset($_POST['rpid']))
        $reply_to = (int)$_POST['rpid'];
    else
        $reply_to = 0;
    $qry = "INSERT INTO `topic` (`poster`, `show_at`, `text`, `postdate`, `post_ip`, `reply_to`) VALUES ({$poster->id}, " . (int)$_POST['show_at'] . ", '" . urlencode($_POST['strNewTopic']) . "', NOW(), " . ip2long($ip) . ", " . $reply_to . ")";
    $ret = Database::SQLquery($qry);

    if (isset($_POST['rpid']) && $_POST['rpid'] && $poster->id != $reply_to) {
        require_once ROOT . "/sendEmail.php";
        sendEmail(User::getUser('id', $reply_to)->email, "【卓信】你收到一条回复，请及时登录查看", "http://{$_SERVER['SERVER_NAME']}/index.php?reply=true");
    }
    sendmsg("successful", "你的留言已经发送.");
}

