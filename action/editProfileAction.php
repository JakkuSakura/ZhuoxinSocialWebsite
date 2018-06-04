<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/3
 * Time: 21:35
 */
require_once ROOT . "/header.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/User.php";
if (!isset($_POST['strProfileText']))
    die("不能直接访问此页面");
$text = $_POST['strProfileText'];

if (User::isLoggedIn()) {
    Database::update("user", "self-introduction", urlencode($text), "`id` = " . User::getLoginUser()->id);
    echo "成功更新信息";
} else {
    echo '对不起，您还没有登录，请登录';
}
?>
