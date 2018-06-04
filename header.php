<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/26
 * Time: 8:51
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

if (!defined("ROOT"))
    define('ROOT', dirname(__FILE__) . "/");
require_once ROOT . "tools.php";
require_once ROOT . "Database.php";
require_once ROOT . "User.php";
date_default_timezone_set('PRC');//设置邮件发送的时间，如果不设置，则会显示其他区的时间
function loginbutton()
{
    if (User::isLoggedIn()) {
        $ur = User::getLoginUser();
        echo <<<TAG
            <div class="item">登录帐号：<strong>$ur->nickname</strong></div>
            <div class="item"><a href="/logout.php">登出</a></div>
TAG;

    } else
        echo <<<TAG
            <div class="item">
                <a href="/login.php" class="ui button">登录</a>
            </div>
            <div class="item">
                <a href="/signup.php" class="ui primary button">注册</a>
            </div>   
TAG;

}

function showmenu($sidemenu = false, $onlysideicon = false)
{
    $user = User::getLoginUser();
    if ($onlysideicon or (!$sidemenu and isMobile()))
        echo <<<TAG
                <a href="javascript:" class="toc item">
                    <i class="sidebar icon"></i>
                </a>
TAG;
    if (!isMobile() or $sidemenu) {
        echo <<<TAG
                    <a href="/" class="item">首页</a>
                    <a href="/profile.php?uid={$user->id}" class="item">我的主页</a>
                    <a href="/friendsList.php" class="item">我的好友</a>
                    <a href="/myMessage.php" class="item">我的消息</a>
                    <a href="/editProfile.php" class="item">编辑资料</a>
                    <a href="/contactus.php" class="item">联系我们</a>
                    <a href="/download.php" class="item">客户端下载</a>
TAG;
        if ($user->is_admin)
            echo "<a href='/X-admin/' class='item'>后台管理</a>";
}
        echo '<div class="right item">';
        loginbutton();
        echo '</div>';
}