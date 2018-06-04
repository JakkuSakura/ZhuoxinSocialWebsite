<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/30
 * Time: 16:58
 */

require_once ROOT . "jcrop/config.php";
require_once ROOT . "MarkdownParser.php";
require_once ROOT . "sendEmail.php";
$user = User::getLoginUser();

function check($name)
{
    if (!isset($_POST[$name]) or $_POST[$name] == "")
        sendmsg("failed", "没有填写{$name}");
}
$chk = ['email', 'nickname', 'reportsubject', 'reporttext'];
foreach ($chk as $item)
    check($item);
sendEmail('qiujiangkun@foxmail.com',"【反馈】{$_POST['nickname']}的反馈——{$_POST['reportsubject']}", "用户向你发送了一条消息， ta的联系邮箱是{$_POST['email']}\n" . $_POST['reporttext'] . "登录ID:".User::getLoginUser()->id);
sendmsg("successful", "发送成功，我们会尽快回复您");