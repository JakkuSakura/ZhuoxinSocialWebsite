<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/22
 * Time: 18:04
 */

require_once "wapheader.php";

$lastpage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ".";

?>

<section class="g-flexview">
    <header class="m-navbar" id="navMain">
        <a href="<?php echo $lastpage; ?>" class="navbar-item"><i class="back-ico"></i></a>
        <div class="navbar-center"><span class="navbar-title">注册卓信</span></div>
    </header>

    <section class="g-scrollview">
        <form action="/action/signupAction.php" method="post" id="frm">
            <script>
                function f(bt) {
                    ajaxSubmit(frm, function (data) {
                        obj = JSON.parse(data);
                        alert(obj.msg);
                        if (obj.state === "successful")
                            window.location.href = "/login.php";
                        else {
                            bt.value = "注册";
                            bt.disabled = false;
                        }
                    });
                }
            </script>
            <div class="m-celltitle">注册信息</div>
            <div class="m-cell">
                <div class="cell-item">
                    <div class="cell-left"><label for="strEmail" style="width: 5em">邮箱:</label></div>
                    <div class="cell-right"><input class="cell-input" type="text" name="strEmail" id="strEmail"
                                                   style="text-align: right"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left"><label for="nickname" style="width: 5em">昵称:</label></div>
                    <div class="cell-right"><input class="cell-input" type="text" name="nickname" id="nickname"
                                                   autocomplete="off" style="text-align: right"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left"><label for="datBirthday" style="width: 5em">生日:</label></div>
                    <div class="cell-right"><input class="cell-input" type="date" name="datBirthday" id="datBirthday"
                                                   style="text-align: right"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left">性别：</div>
                    <label class="cell-right cell-arrow">
                        <select class="cell-select" name="strGender">
                            <option value="">请选择性别</option>
                            <option value="m">男</option>
                            <option value="f">女</option>
                            <option value="s">未知</option>
                        </select>
                    </label>
                </div>
                <div class="cell-item">
                    <div class="cell-left"><label for="passPassword" style="width: 5em">密码:</label></div>
                    <div class="cell-right"><input class="cell-input" type="password" name="passPassword"
                                                   id="passPassword" style="text-align: right" autocomplete="off"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left"><label for="strCaptcha">验证码：</label></div>
                    <div class="cell-right"><input type="text" class="cell-input" id="strCaptcha" name="strCaptcha"
                                                   style="text-align: right; margin-right: .3rem">
                        <img src="/Captcha.php" id="cpt"
                             onclick="this.src = 'Captcha.php?' + Math.random()"/>
                    </div>
                </div>
                <label class="cell-item">
                    <span class="cell-left">我接受
                                <a href="privacy.php" target="_blank">《服务协议》</a></span>
                    <label class="cell-right">
                        <input type="checkbox" class="m-switch-old" name="blnTerms" id="blnTerms">
                        <span class="m-switch"></span>
                    </label>
                </label>
            </div>
            <input type="button" class="btn-block btn-primary" value="注册"
                   onclick="this.disabled=true; this.value='working';f(this)">
        </form>
    </section>
    <?php
    require_once "wapfooter.php";
    ?>
