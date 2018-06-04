<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/28
 * Time: 9:45
 */
require_once "header.php";
?>
    <!DOCTYPE html>
    <html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properties -->
        <title>卓信社交——专门为青少年准备的社交平台</title>
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

                })
            ;

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

            function sbmt() {
                ajaxSubmit(myForm, function (data) {
                    obj = JSON.parse(data);
                    if (obj.state === "successful") {
                        $("#message").html(obj.msg + "我们在您的邮箱里发送了一封邮件，您需要打开邮件中的链接进行邮箱验证。");
                        $('.small.modal')
                            .modal('show')
                        ;
                        goback();
                    }
                    else {
                        $("#msg").html(obj.msg);
                        $("#wrn").attr("class", "ui warning message");
                        $("#sdbt").val("注册").attr("disabled", false);
                    }
                });

            }
        </script>
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


    <!-- Page Contents -->
<div class="pusher">
    <div class="ui fixed menu">
        <div class="ui container">
            <?php showmenu(false) ?>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br><br><br>
    <div class="ui two column doubling stackable grid container">
        <div class="row">
            <h1 class="ui dividing header">
                注册帐号
            </h1>
        </div>
        <div class="column">
            <div class="ui warning message hidden" id="wrn">
                <i class="close icon"></i>
                <div id="msg"></div>
            </div>
            <div class="ui info message" id="tip">
                <i class="close icon"></i>
                请使用真实信息注册。我们检测虚假、恶意注册，一经发现会封禁帐号和注册IP，请慎行之。我们会定期检测长时间不活跃、无内容帐号，并进行处理。
            </div>
            <form class="ui form" action="/action/signupAction.php" method="post" name="myForm" id="myForm">
                <div class="field">
                    <label class="bold" for="strEmail">邮箱地址:</label>
                    <div class="field">
                        <input class="text" maxlength="70" type="text" size="35" name="strEmail"
                               id="strEmail" value="">
                    </div>

                </div>
                <div class="field">
                    <label class="bold" for="nickname">昵称:</label>
                    <div class="field">
                        <input class="text" maxlength="70" type="text" size="35" name="nickname"
                               id="nickname" value="">
                    </div>

                </div>
                <div class="field">
                    <label class="bold" for="datBirthday">出生日期:</label>
                    <div class="field">
                        <input id="datBirthday" name="datBirthday" type="date">

                    </div>
                </div>

                <div class="field">
                    <label class="bold" for="strGender">你的性别:</label>
                </div>
                <div class="field">
                    <select class="text" id="strGender" name="strGender">
                        <option value="u">
                            --
                        </option>
                        <option value="f">
                            女
                        </option>
                        <option value="m">
                            男
                        </option>
                        <option value="s">
                            保密
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label for="passPassword" class="bold">密码:</label>
                    <div class="field">
                        <input type="password" class="text" maxlength="50" id="passPassword"
                               name="passPassword" value="" aria-autocomplete="list">
                    </div>

                </div>
                <div class="field">

                    <label for="strCaptcha" class="bold">验证码:</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" class="text" maxlength="50" id="strCaptcha"
                                   name="strCaptcha" value="" aria-autocomplete="list">
                        </div>
                        <div class="field">
                            <img src="/Captcha.php" id="cpt" class="ui small right floated image"
                                 onclick="this.src = '/Captcha.php?' + Math.random()"/>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" name="blnTerms" id="blnTerms" value="1">
                        <label for="blnTerms">我接受<a href="privacy.php" target="_blank">《服务协议》</a></label>
                    </div>
                    <input type="button" value="注册" class="ui right floated primary button" title="注册" id="sdbt"
                           onclick="this.disabled = true; sbmt()">

                </div>

            </form>
        </div>


        <div class="column">
            <div class="ui info message">
                <h3 class="ui header">
                    邮箱地址拼写正确?
                </h3>
                <p>
                    如果你的邮箱地址拼写错误，你将无法收到验证邮件。
                </p>
                <h3 class="ui header">
                    出生日期选好了?
                </h3>
                <p>
                    请务必输入真实的生日 <span class="redColor">生日在注册后无法更改!</span>
                </p>
                <h3 class="ui header">
                    没有收到验证邮件？
                </h3>
                <p>
                    检查你的垃圾箱、已删除邮件、广告邮件、邮件黑名单，有时邮件会出现在这里。
                </p>
                <h3 class="ui header">
                    已经注册？
                </h3>
                <p>
                    点击右上角登录你的帐号。
                </p>

            </div>
        </div>

    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<?php
require_once "footer.php";
