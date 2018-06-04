<?php
require_once "header.php";
require_once "tools.php";

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>忘记密码 - 卓信社交</title>

    <link rel="stylesheet" type="text/css" href="/scripts/css/semantic.min.css">
    <script src="/scripts/jquery-3.3.1.min.js"></script>
    <script src="/scripts/semantic.min.js"></script>
    <script src="/scripts/myTools.js"></script>

    <script>
        $(document)
            .ready(function () {

                // create sidebar and attach to menu open
                $('.ui.sidebar')
                    .sidebar('attach events', '.toc.item')
                ;

            })
        ;

    </script>
    <style>

    </style>

    <head>
<body>
<div class="ui small modal">
    <i class="close icon"></i>
    <div class="header">
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
        <?php showmenu(false); ?>
    </div>
</div>

<br>
<div class="pusher">
    <div class="ui raised very padded text container segment" style="min-height: 60rem">
        <br><br><br>
        <h1 class="ui dividing header">忘记密码</h1>

        <form action="/action/forgetpwdAction.php" method="post" name="myForm" class="ui form">
            <label class="ui ribbon label" for="strEmail">邮箱地址:</label>
            <div class="field">
                <input class="bigInput" type="text" name="strEmail" id="strEmail" value="">
            </div>

            <input id="sbbt" type="button" value="重置密码" class="ui primary button" title="重置密码"
                   onclick="this.disabled=true;ajaxSubmit(myForm, function(data) {
                                     obj = JSON.parse(data);
                                     myalert(obj.msg);
                                     if (obj.state !== 'successful')
                                     {
                                         $('#sbbt').attr('disabled', false);
                                     }
                               });">

        </form>
    </div>
    <?php
    require_once "footer.php";
    ?>
