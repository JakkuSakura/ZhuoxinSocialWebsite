<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/14
 * Time: 15:16
 */
require_once "header.php";
require_once "User.php";
$die = $suc = "";
function action()
{
    global $die;
    if (!isset($_GET['email']) or !isset($_GET['code'])) {
        $die = "请不要直接访问这个页面";
        return;
    }
    $email = $_GET['email'];
    $key = $_GET['code'];

    $ur = User::getUser('email', addslashes($email));
    if (!$ur->valid()) {
        $die = "邮箱没有注册";
        return;
    }

    if ($ur->obj->reset_key != $key) {
        $die = "此验证已经失效";
        return;
    }
    $newpswd = rand(10000000, 1000000000);
    Database::updateItems("user", ["password", "reset_key"], [md5("FUCK" . $newpswd), ""], "id=" . $ur->id);
    global $suc;
    $suc = "新密码是{$newpswd}<br>请保存好，并及时修改密码";
    return;
}

action();
?>

    <!DOCTYPE html>
    <html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properties -->
        <title>重置密码 - 卓信社交</title>

        <link rel="stylesheet" type="text/css" href="/scripts/css/semantic.min.css">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>

        <script src="/scripts/jquery-3.3.1.min.js"></script>
        <script src="/scripts/semantic.min.js"></script>

        <style type="text/css">

        </style>
        <script>
            $(document)
                .ready(function () {


                })
            ;

        </script>
        <style>

        </style>

        <head>
<body>

    <!-- Sidebar Menu -->
    <div class="ui vertical inverted sidebar menu">
        <?php showmenu(true); ?>
    </div>

    <div class="ui fixed menu">
        <div class="ui container">
            <?php showmenu(false); ?>
        </div>
    </div>

<div class="pusher">
    <br>
    <div class="ui raised very padded text container segment" style="min-height: 60rem">
        <h1 class="ui dividing header">重置密码</h1>
        <?php
        if ($suc)
            echo "<div class=\"ui info message\">{$suc}</div>";
        else
            echo "<div class=\"ui error message\">{$die}</div>";
        ?>


    </div>


<?php
require_once "footer.php";
?>