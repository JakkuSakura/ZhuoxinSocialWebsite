<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <title>登陆卓信</title>
    <link rel="stylesheet" href="scripts/css/semantic.min.css">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>
    <script src="scripts/jquery-3.3.1.min.js"></script>
    <script src="scripts/semantic.min.js"></script>
    <script src="scripts/myTools.js"></script>
    <script src="scripts/adblock.js"></script>
    <script type="text/javascript">
        var childWindow;

        function toQzoneLogin() {
            var openUrl = "action/qqloginAction.php";//弹出窗口的url
            var iWidth = 450; //弹出窗口的宽度;
            var iHeight = 320; //弹出窗口的高度;
            var iTop = (window.screen.availHeight - 30 - iHeight) / 2; //获得窗口的垂直位置;
            var iLeft = (window.screen.availWidth - 10 - iWidth) / 2; //获得窗口的水平位置;
            childWindow = window.open(openUrl, "", "height=" + iHeight + ", width=" + iWidth + ", top=" + iTop + ", left=" + iLeft + ',menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1');



        }

        function closeChildWindow() {
            childWindow.close();
        }

        $.ready(function () {
                $('.ui.checkbox')
                    .checkbox()
                ;
                $('.message .close')
                    .on('click', function () {
                        $(this)
                            .closest('.message')
                            .transition('fade')
                        ;
                    })
                ;
            }
        );
    </script>
    <style>
        .ui.ribbon.label {
            margin-left: 1rem;
        }
    </style>
    <head>
<body>


<div class="pusher">
    <div class="ui raised very padded text container segment">

        <a href="."><img src="/img/biglogo.png" alt="卓信" class="ui centered medium image"></a>
        <h1 class="ui dividing header">
            登录卓信
        </h1>
        <form action="/action/loginAction.php" method="post" id="myfmt" name="myfmt" class="ui form">
            <div class="ui warning message hidden" id="wrn">
                <i class="close icon"></i>
                <div id="msg"></div>
            </div>

            <label class="ui green ribbon label">邮箱</label>
            <div class="field">
                <input type="text" name="strLogInEmail" id="strLogInEmail" placeholder="YourEmailHere">
            </div>

            <label class="ui blue ribbon label">密码</label>
            <div style="float: right">
                <a href="/forgetpwd.php">忘记密码?</a>
            </div>
            <div class="field">
                <input type="password" name="passLogInPassword" id="passLogInPassword" placeholder="YourPasswordHere">
            </div>
            <div class="field">
                <div class="ui toggle checkbox">
                    <input name="blnRememberMe" id="blnRememberMe" type="checkbox">
                    <label for="blnRememberMe">保持登录</label>
                </div>
                <input type="button" value="登录" onclick="sendfm()" class="ui right floated primary button"/>
            </div>
            <div class="field">
                <a onclick='toQzoneLogin()'><img src="/img/qq_login.png"></a>
            </div>

            <script>
                function goback() {
                    window.location.href = "<?php
                        if (isset($_POST['back']))
                            echo htmlspecialchars($_POST['back']);
                        else if (isset($_SERVER['HTTP_REFERER']))
                            echo $_SERVER['HTTP_REFERER'];
                        else
                            echo ".";
                        ?>";
                }

                function sendfm() {
                    ajaxSubmit(myfmt, function (data) {
                        result = JSON.parse(data);
                        if (result.state === "successful") {
                            goback();
                        }
                        else {
                            window.alert(result.msg);
                        }
                    });
                }
            </script>


        </form>
    </div>
</body>
</html>