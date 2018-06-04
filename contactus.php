<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/29
 * Time: 9:46
 */
require_once "header.php";
require_once ROOT . "jcrop/config.php";
require_once ROOT . "MarkdownParser.php";
require_once ROOT . "sendEmail.php";

$user = User::getLoginUser();

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <title>编辑资料 卓信</title>
    <link rel="stylesheet" href="/scripts/css/semantic.min.css">
    <script src="/scripts/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/jcrop/jcrop/jquery.Jcrop.min.js"></script>
    <script src="/scripts/semantic.min.js"></script>
    <script src="/scripts/myTools.js"></script>
    <script src="/scripts/adblock.js"></script>
    <link type="text/css" rel="stylesheet" href="/jcrop/file-uploader/fileuploader.css"/>
    <script type="text/javascript" language="javascript" src="/jcrop/file-uploader/fileuploader.min.js"></script>


    <link type="text/css" rel="stylesheet" href="/jcrop/jcrop/jquery.Jcrop.min.css"/>
    <script type="text/javascript" language="javascript" src="/jcrop/jcrop/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" language="javascript">
        $(document)
            .ready(function () {
                // create sidebar and attach to menu open
                $('.ui.sidebar')
                    .sidebar('attach events', '.toc.item')
                ;

            });

    </script>

    <style>
        .ui.ribbon.label {
            margin-left: 1rem;
        }
    </style>

    <head>
<body>

<div class="ui small modal">
    <i class="close icon"></i>
    <div class="header" id="header">
        提示
    </div>
    <div class="content">
        <div class="description">
            <p id="message"></p>
        </div>
    </div>
    <div class="actions">
        <div class="ui black deny button">
            OK
        </div>
    </div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
    <?php showmenu(true); ?>
</div>

<div class="ui fixed menu">
    <div class="ui container">
        <?php showmenu( false ); ?>
    </div>
</div>

<div class="pusher">
    <div class="ui raised very padded text container segment">

        <img src="/img/biglogo.png" alt="卓信" class="ui centered medium image">
        <h3 class="ui dividing header">反馈作者</h3>
        <form action="/action/contactusAction.php" method="post" class="ui form" id="protext">
            <label class="ui ribbon label" for="email">你的联系邮箱</label>
            <div class="field">
                <input type="text" name="email" id="email" value="<?php if ($user->valid()) echo $user->email?>">
            </div>
            <label class="ui ribbon label" for="nickname">你的称呼</label>
            <div class="field">
                <input type="text" name="nickname" id="nickname" value="<?php if ($user->valid()) echo $user->nickname?>">
            </div>
            <label class="ui ribbon label" for="reportsubject">反馈主题</label>
            <div class="field">
                <input type="text" name="reportsubject" id="reportsubject" value="问题反馈">
            </div>
            <label class="ui ribbon label" for="reporttext">反馈内容</label>
            <div class="field">
                <textarea name="reporttext" id="reporttext"></textarea>
            </div>
            <input type="button" value="发送" class="ui right floated primary button" onclick="ajaxSubmit(protext, function(data) {
              obj = JSON.parse(data);myalert(obj.msg);if (obj.state === 'successful') $('#reporttext').val('');
            })">
        </form>
    </div>
<?php
require_once "footer.php";
