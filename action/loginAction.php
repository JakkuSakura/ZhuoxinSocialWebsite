<?php
require_once "configure.php";

require_once ROOT . "/User.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/tools.php";
function login($email, $pwd, $keep, $raw_password = false)
{
    if ($keep)
        $lifeTime = 24 * 3600;
    else
        $lifeTime = 2 * 3600;
    session_set_cookie_params($lifeTime);

    $usr = User::userLogin($email, $raw_password ? $pwd : md5("FUCK" . $pwd));
    if ($usr->valid()) {
        $_SESSION['olemail'] = $usr->email;
        $_SESSION['olid'] = $usr->id;
        $_SESSION['online'] = true;
    } else {
        $_SESSION['online'] = false;
    }
    header('Content-Type:application/json');
    if ($usr->valid()) {
        Database::update('user', 'last_login', "'+NOW()+'", "id={$usr->id}");
        return true;
    } else {
        return false;
    }
}

if (!isset($noautologin)) {
    $email = $_POST['strLogInEmail'];
    $pwd = $_POST['passLogInPassword'];
    $keep = isset($_POST['blnRememberMe']);
    if (login($email, $pwd, $keep)) {
        sendmsg("successful", "登录成功");
    } else {
        sendmsg("falied", "登录失败");
    }
}
?>