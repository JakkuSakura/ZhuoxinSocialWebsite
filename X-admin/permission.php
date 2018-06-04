<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/4/3
 * Time: 16:43
 */
require_once "header.php";
function getPermission($userid)
{
    $qr = Database::SQLquery("SELECT * FROM `permission` WHERE `id`=" . $userid);
    if (mysqli_num_rows($qr))
        return mysqli_fetch_assoc($qr);
    else
        return mysqli_fetch_assoc(Database::SQLquery("SELECT * FROM `permission` WHERE `id`=0"));

}

function check($oper)
{
    $user = User::getLoginUser();
    if (!$user->valid()) {
        sendmsg("failed", "对不起，您没有登录");
    }
    $permission = getPermission($user->id);
    if ($permission[$oper] <= 0) {
        sendmsg("failed", "对不起，您没有权限");
    }

}
