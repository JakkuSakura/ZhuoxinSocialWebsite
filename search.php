<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/29
 * Time: 9:46
 */
require_once "header.php";

$user = User::getLoginUser();

function v($a, $b, $default = "")
{
    if (isset($a[$b]))
        return $a[$b];
    else
        return $default;
}

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <title>搜索用户 卓信</title>
    <link rel="stylesheet" href="/scripts/css/semantic.min.css">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>

    <script src="/scripts/jquery-3.3.1.min.js"></script>
    <script src="/scripts/semantic.min.js"></script>
    <script src="/scripts/myTools.js"></script>
    <script src="/scripts/adblock.js"></script>

    <script type="text/javascript" language="javascript">
        <?php

        if (!User::isLoggedIn())
            echo "window.location.href='/login.php';"
        ?>

        $(document)
            .ready(function () {
                // create sidebar and attach to menu open
                $('.ui.sidebar')
                    .sidebar('attach events', '.toc.item')
                ;

                $(".special.cards .image").dimmer({
                    on: 'hover'
                });
            });

        function addfriend(id) {
            $.post("/action/friendsAction.php",
                {
                    optype: "apply",
                    user1id: id
                },
                function (data, status) {
                    obj = JSON.parse(data);
                    $("#message").html(obj.msg);
                    $('.small.modal')
                        .modal('show')
                    ;
                });
        }

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
<div class="pusher">
    <br><br><br><br><br>
    <img src="/img/biglogo.png" alt="卓信" class="ui centered medium image">
    <div class="ui raised very padded text container segment">

        <h1 class="ui dividing header">
            搜索用户
        </h1>
        <form action="search.php" method="post" class="ui form" id="searchform">
            <h3 class="ui dividing header">基本信息</h3>

            <label class="ui ribbon label">昵称</label>
            <div class="field">
                <input type="text" class="cell-input" placeholder="请输入您要检索的昵称" autocomplete="off" name="strName"
                       value="<?php echo v($_POST, 'strName'); ?>">
            </div>

            <label class="ui ribbon label">年龄</label>

            <div class="field">
                <div class="fields">
                    <div class="inline field">
                        <input type="text" name="wanna_down" autocomplete="off"
                               value="<?php echo v($_POST, 'wanna_down', 10); ?>">
                        <label for="">岁到</label>

                    </div>
                    <div class="inline field">
                        <input type="text" name="wanna_up" autocomplete="off"
                               value="<?php echo v($_POST, 'wanna_up', 40); ?>">
                        <label for="">岁</label>
                    </div>

                </div>
            </div>

            <label class="ui ribbon label">搜索的性别</label>
            <div class="fields">
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" name="cb_male" id="cb_male" checked
                               value="<?php echo v($_POST, 'cb_male'); ?>">
                        <label for="cb_male">男</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="checkbox" name="cb_female" id="cb_female" checked
                               value="<?php echo v($_POST, 'cb_female'); ?>">
                        <label for="cb_female">女</label>

                    </div>

                </div>
            </div>


            <div class="ui dividing header">其他信息</div>
            <label class="ui ribbon label">省份</label>
            <div class="field">
                <input type="text" placeholder="请输入您要检索的省份" autocomplete="off" name="strProvince">
            </div>

            <label class="ui ribbon label">城市</label>
            <div class="field">
                <input type="text" placeholder="请输入您要检索的城市" autocomplete="off" name="strCity">
            </div>

            <label class="ui ribbon label">兴趣爱好</label>
            <div class="field">
                <input type="text" placeholder="请输入您要检索的兴趣爱好" autocomplete="off" name="strHobby">
            </div>
            <input type="hidden" id="page" name="page" value="<?php echo v($_POST, 'page', 1); ?>">
            <input type="submit" class="ui primary button" value="搜索">

        </form>

        <?php
        if (isset($_POST['page'])) {
            echo "<h3 class='ui dividing header'>搜索结果</h3>";
            $condlist = ['wanna_down', 'wanna_up', 'cb_female', 'cb_male', 'strName', 'strProvince', 'strCity', 'strHobby'];
            $cond = [];
            foreach ($condlist as $item) {
                if (isset($_POST[$item]) && $_POST[$item] != "") {
                    $cond[$item] = addslashes(htmlspecialchars(urlencode($_POST[$item])));
                }
            }
            $qrst = "true ";
            if (isset($cond['wanna_down']))
                $qrst .= "and birthday <= NOW() - Interval {$cond['wanna_down']} year ";
            if (isset($cond['wanna_up']))
                $qrst .= "and birthday >= NOW() - Interval {$cond['wanna_up']} year ";
            if (isset($cond['cb_male']) and isset($cond['cb_female']))
                ;
            else if (isset($cond['cb_female']))
                $qrst .= "and gender='f' ";
            else if (isset($cond['cb_male']))
                $qrst .= "and gender='m' ";
            if (isset($cond['strName']))
                $qrst .= "and nickname='" . $cond['strName'] . "' ";
            if (isset($cond['strProvince']))
                $qrst .= "and province='" . $cond['strProvince'] . "' ";
            if (isset($cond['strCity']))
                $qrst .= "and city='" . $cond['strCity'] . "' ";
            if (isset($cond['strHobby']))
                $qrst .= "and INSTR(`habits`, '" . $cond['strHobby'] . "') ";
            $qrst .= "LIMIT " . ((int)$_POST['page'] - 1) * 12 . ", 12";
            //                echo $qrst;
            $qr = Database::query('user', $qrst);
            //                if(is_string($qr))
            //                    echo $qr;
            if (is_string($qr))
                echo "<div class='ui error message'>输入有误</div>";
            else {
                echo "<div class='ui info message'>本页有结果 " . mysqli_num_rows($qr) . " 个</div>";
                echo '<div class="ui ' . (isMobile() ? "two" : "three") . ' special cards">';
                while ($obj = mysqli_fetch_object($qr)) {
                    $ur = new User($obj);
                    if (!$ur->valid() or $ur->banned)
                        continue;
                    $ur->getFriends();
                    $date = Time::diffdate_proximate($ur->signup_date);
                    $diff = Time::diffdate_proximate($ur->last_login);
                    echo <<<TAG
                        <div class='ui card'>
                              <div class='blurring dimmable image'>
                                   <div class='ui dimmer'>
                                        <div class='content'>
                                            <div class='center'>
                                                <div class='ui inverted button' onclick='addfriend($ur->id)'><i class="add user icon"></i>添加好友</div>
                                            </div>
                                        </div>
                                   </div>
                                   <img src='/{$ur->img_path}' alt='{$ur->nickname}' title='{$ur->nickname}' style='max-height: 18em'>
                              </div>
                              <div class='content'>
                                 <div class='header'><a href='profile.php?uid={$ur->id}' style='color: black'>{$ur->nickname}</a></div>
                                 <div class='meta'><span class='date'>注册于{$date}</span><br><span class='date'>登录于{$diff}</span></div>
                                 <div class='description'>{$ur->province} {$ur->city}</div>
                              </div>
                              <div class='extra content'><i class='user icon'></i>拥有好友：{$ur->friendsnum}</div>
                               
                          </div>
TAG;
                }
                echo <<<TAG
                    <div class="ui primary button" onclick="lastpage()">上一页</div>
                    <div class="ui primary button" onclick="nextpage()">下一页</div>
TAG;

                echo "</div>";
                echo <<<TAG
                <script>
                function nextpage() {
                    if ({$_POST['page']} < 100)
                    {
                        fm = $("#searchform");
                        $("#page").val({$_POST['page']} + 1);
                        fm.submit();      
                    }
                }
                function lastpage() {
                    if ({$_POST['page']} > 1)
                    {
                        fm = $("#searchform");
                        $("#page").val({$_POST['page']} - 1);
                        fm.submit();
                    }
                }
                </script>
TAG;

            }
        }
        ?>
    </div>

</body>
</html>

