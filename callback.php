<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/22
 * Time: 22:33
 */

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once "qqlogin.php";
if (!(isset($_GET['state']) && isset($_GET['code']))) {
    exit;
}
$qq = new \Component\QQ_LoginAction();
$acs = $qq->qq_callback();
$oid = $qq->get_openid();
require_once "User.php";
$login = User::getUser('bind_openid', $oid);
if (!isset($_SESSION['openid'])) {
    header("location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
if ($login->valid()) {
    $noautologin = true;
    require_once "action/loginAction.php";
    $ok = login($login->email, $login->obj->password, false, true);
    header("Content-type:text/html;charset=utf-8");
    ?>
    <!doctype html>
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Successful</title>
        <head>
    <body>
    <script>
        window.opener.goback();
        window.close();
    </script>
    </body>
    </html>
    <?php
}

$user_data = $qq->get_user_info();
$_SESSION['qq_data'] = $user_data;
/*
 Array(
[ret] => 0 
[msg] => 
[is_lost] => 0 
[nickname] => 一世真情 
[gender] => 男 
[province] => 山东 
[city] => 潍坊 
[year] => 2001 
[figureurl] => http://qzapp.qlogo.cn/qzapp/101468292/894A0196C4E3B6BEE91BC1A37F5BBCAD/30 
[figureurl_1] => http://qzapp.qlogo.cn/qzapp/101468292/894A0196C4E3B6BEE91BC1A37F5BBCAD/50 
[figureurl_2] => http://qzapp.qlogo.cn/qzapp/101468292/894A0196C4E3B6BEE91BC1A37F5BBCAD/100 
[figureurl_qq_1] => http://thirdqq.qlogo.cn/qqapp/101468292/894A0196C4E3B6BEE91BC1A37F5BBCAD/40 
[figureurl_qq_2] => http://thirdqq.qlogo.cn/qqapp/101468292/894A0196C4E3B6BEE91BC1A37F5BBCAD/100 
[is_yellow_vip] => 0 
[vip] => 0 
[yellow_vip_level] => 0 
[level] => 0 
[is_yellow_year_vip] => 0 )
*/

require_once "header.php";
require_once "sendEmail.php";
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


            function sbmt() {
                ajaxSubmit(myForm, function (data) {
                    obj = JSON.parse(data);
                        myalert(obj.msg)
                    if (obj.state === "successful") {
                        setTimeout("window.opener.goback();window.close();", 3000);
                    }
                    else {
                        $("#sdbt").val("注册").attr("disabled", false);
                    }
                });

            }

            function lgsm() {
                ajaxSubmit(bindform, function (data) {
                    obj = JSON.parse(data);
                    myalert(obj.msg);
                    if (obj.state === "successful") {
                        setTimeout("window.opener.goback();window.close();", 3000);
                    }
                    else {
                        $("#lgbt").val("注册").attr("disabled", false);
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
    <div class="ui two column doubling stackable grid container">
        <div class="row">
            <h1 class="ui dividing header">绑定qq帐号</h1>
        </div>
        <div class="ui info message">你的qq昵称是：<?php echo $user_data['nickname'] ?> </div>

        <div class="column">
            <h2 class="ui dividing header">
                注册新帐号
            </h2>
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
                               id="nickname" value="<?php echo $user_data['nickname'] ?>">
                    </div>

                </div>
                <div class="field">
                    <label class="bold" for="datBirthday">出生日期:</label>
                    <div class="field">
                        <input id="datBirthday" name="datBirthday" type="date"
                               value="<?php echo $user_data['year'] ?>-01-01">
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
                        <option value="f" <?php if ($user_data['gender'] === "男") echo "selected"; ?> >
                            女
                        </option>
                        <option value="m">
                            男
                        </option>
                        <option value="s" <?php if ($user_data['gender'] === "男") echo "selected"; ?> >
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
            <h2 class="ui dividing header">绑定已有帐号</h2>
            <form action="/action/bindAction.php" method="post" id="bindform" class="ui form">
                <label class="ui ribbon label">邮箱</label>
                <div class="field">
                    <input type="text" name="strEmail">
                </div>
                <label class="ui ribbon label">密码</label>
                <div class="field">
                    <input type="password" name="passPassword">
                </div>
                <input type="button" value="登录" class="ui right floated primary button" title="登录" id="lgbt"
                       onclick="this.disabled = true; lgsm()">
            </form>
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

