<?php
require_once "configure.php";
require_once ROOT . "/sessionHandle.php";
require_once ROOT . "/User.php";
header('content-type:text/html;charset=utf-8');
if (User::isLoggedIn()) {
    $user = User::getLoginUser();
    if (isset($_POST['strNickName'])) {
        $nickname = $_POST['strNickName'];
        if (!preg_match("/^(?!_)(?!.*?_$)[a-zA-Z0-9_ \x{4e00}-\x{9fa5}]+$/u", $nickname)) {
            $nameErr = "昵称只允许字母、汉字、数字、下划线和空格！";
            die($nameErr);
        }
    }

    if ($_POST['strGender'] == "")
        die("性别不能为空");
    $items = array("realname", "nickname", "gender", "province", "city", "relationship_status", "habits");
    $values = array(urlencode($_POST['strRealName']), urlencode($_POST['strNickName']), $_POST['strGender'], urlencode($_POST['strState']),
        urlencode($_POST['strCity']), $_POST['intRelationshipStatus'], urlencode($_POST['strHabit']));
    if ($_POST['strOldPassword'] != "" || $_POST['strNewPassword'] != "") {
        if ($_POST['strOldPassword'] == "" || $_POST['strNewPassword']){
            die("你必须同时输入旧密码和新密码");
        }
        if ($_POST['strOldPassword'] == $user->obj->password) {
            $items[] = "password";
            $values[] = addslashes($_POST['strNewPassword']);
        }
        else
        {
            die("旧密码不匹配");
        }
    }
    if (Database::updateItems("user", $items, $values, "id='" . $user->id . "'") == 1)
        echo "更新成功";
    else
        echo "更新失败";
} else {
    echo "请登录后重试";
}
?>