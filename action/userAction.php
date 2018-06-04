<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/15
 * Time: 21:32
 */
require_once ROOT . "/User.php";
require_once ROOT . "/tools.php";

if (!(User::isLoggedIn() and User::getLoginUser()->is_admin))
    sendmsg("failed", "用户权限不足");
if (!isset($_POST['user1id']))
    sendmsg("failed", "缺少参数user1id");
$_POST['user1id'] = (int)$_POST['user1id'];
if (!isset($_POST['optype']))
    sendmsg("failed", "缺少参数optype");
switch ($_POST['optype'])
{
    case "ban":
        Database::update("user","banned", "1", "id={$_POST['user1id']}");
        sendmsg("successful", "操作成功");
        break;
    case "deban":
        Database::update("user","banned", "0", "id={$_POST['user1id']}");
        sendmsg("successful", "操作成功");
        break;
    default:
        sendmsg("failed", "未知操作");
        break;
}