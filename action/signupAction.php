<?php
require_once "configure.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/tools.php";
require_once ROOT . "/User.php";
require_once ROOT . "/sendEmail.php";

$noautologin = true;
require_once "loginAction.php";
if (!isset($_POST['strEmail']))

    sendmsg("failed", "邮箱不能为空");
$email = addslashes($_POST['strEmail']);
//if (!preg_match("/([\w\d\-]+\@[\w\-]+\.[\w\-]+)/", $email))
//    sendmsg("failed", "无效的 email 格式！");
if (User::getUser('email', $email)->valid())
    sendmsg("failed", "您的邮箱已经注册过，请勿重复注册。如需帮助，请咨询管理员 956269867@qq.com");
if (!isset($_POST['nickname']))
    sendmsg("failed", "昵称不能为空");

$nickname = addslashes($_POST['nickname']);
if (!preg_match("/^(?!_)(?!.*?_$)[a-zA-Z0-9_ \x{4e00}-\x{9fa5}]+$/u", $nickname))
    sendmsg("failed", "昵称只允许字母、汉字、数字、下划线和空格！");
if (!isset($_POST['datBirthday']))
    sendmsg("failed", "出生日期为空！");
$datBirthday = addslashes($_POST['datBirthday']);
if (!isset($_POST['strGender']))
    sendmsg("failed", "性别不能为空");
$gender = addslashes($_POST['strGender']);
if ($gender != 'm' && $gender != 'f' && $gender != 's') {
    sendmsg("failed", "性别未选！");
}
if (!isset($_POST['passPassword']))
    sendmsg("failed", "密码为空");
$password = $_POST['passPassword'];
if (strlen($password) < 6 || strlen($password) > 16) {
    sendmsg("failed", "密码长度不合适！");
}
$password = md5("FUCK" . $password);

if (!isset($_SESSION['captcha_code']) or !isset($_POST['strCaptcha']) or $_SESSION['captcha_code'] != strtolower($_POST['strCaptcha'])) {
    sendmsg("failed", "你必须正确地输入验证码");
}
if (!isset($_POST['strEmail']) or empty($_POST['blnTerms'])) {
    sendmsg("failed", "你没有阅读并同意注册条款");
}


$ip = getIP();
if ($ip == "::1")
    $ip = "127.0.0.1";
$openid = isset($_SESSION['openid']) ? $_SESSION['openid'] : "";
sendVeriEmail($email, $nickname); //if cannot then disallow to register
$query = "INSERT INTO `user` (`id`, `email`, `nickname`, `gender`, `birthday`, `password`, `signup_date`, `img_path`, `signup_ip`, `bind_openid`) VALUES (NULL, '{$email}', '" . urlencode($nickname) . "', '{$gender}', '$datBirthday', '{$password}', NOW(), 'img/nophoto_48x48.png', " .
    ip2long($ip) . ", '{$openid}');";
$rst = Database::SQLquery($query);
login($email, $password, false);
sendmsg("successful", "注册完成，您需要打开您的邮箱进行验证，或者直接登录", "json");
