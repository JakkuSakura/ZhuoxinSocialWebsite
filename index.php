<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/26
 * Time: 8:50
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
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>

        <style type="text/css">

            #showpage {

                background-image: url("/img/showpage.jpg");
                /* 背景图垂直、水平均居中 */

                background-position: center center;

                /* 背景图不平铺 */

                background-repeat: space;

                /* 当内容高度大于图片高度时，背景图像的位置相对于viewport固定 */

                background-attachment: fixed;

                /* 让背景图基于容器大小伸缩 */

                background-size: cover;

            }

            .hidden.menu {
                display: none;
            }

            .masthead.segment {
                min-height: 700px;
                padding: 1em 0em;
            }

            .masthead .logo.item img {
                margin-right: 1em;
            }

            .masthead .ui.menu .ui.button {
                margin-left: 0.5em;
            }

            .masthead h1.ui.header {
                margin-top: 3em;
                margin-bottom: 0em;
                font-size: 4em;
                font-weight: normal;
            }

            .masthead h2 {
                font-size: 1.7em;
                font-weight: normal;
            }

            .ui.vertical.stripe {
                padding: 8em 0em;
            }

            .ui.vertical.stripe h3 {
                font-size: 2em;
            }

            .ui.vertical.stripe .button + h3,
            .ui.vertical.stripe p + h3 {
                margin-top: 3em;
            }

            .ui.vertical.stripe .floated.image {
                clear: both;
            }

            .ui.vertical.stripe p {
                font-size: 1.33em;
            }

            .ui.vertical.stripe .horizontal.divider {
                margin: 3em 0em;
            }

            .quote.stripe.segment {
                padding: 0em;
            }

            .quote.stripe.segment .grid .column {
                padding-top: 5em;
                padding-bottom: 5em;
            }

            .footer.segment {
                padding: 5em 0em;
            }

            .large.top.fixed.menu .toc.item,
            .secondary.pointing.menu .toc.item {
                display: none;
            }

            @media only screen and (max-width: 600px) {

                .fixed.menu {
                    display: none;
                }

                .secondary.pointing.menu .item,
                .secondary.pointing.menu .menu {
                    display: none;
                }

                .large.top.fixed.menu .toc.item,
                .secondary.pointing.menu .toc.item {
                    display: block;
                }

                .masthead.segment {
                    min-height: 350px;

                }

                .masthead h1.ui.header {
                    font-size: 2em;
                    margin-top: 1.5em;
                }

                .masthead h2 {
                    margin-top: 0.5em;
                    font-size: 1.5em;
                }
            }


        </style>

        <script src="/scripts/jquery-3.3.1.min.js"></script>
        <script src="/scripts/semantic.min.js"></script>
        <script>
            $(document)
                .ready(function () {
                    // fix menu when passed
                    $('.masthead')
                        .visibility({
                            once: false,
                            onBottomPassed: function () {
                                $('.fixed.menu').transition('fade in');
                            },
                            onBottomPassedReverse: function () {
                                $('.fixed.menu').transition('fade out');
                            }
                        })
                    ;

                    // create sidebar and attach to menu open
                    $('.ui.sidebar')
                        .sidebar('attach events', '.toc.item')
                    ;

                    $(".special.cards .image").dimmer({
                        on: 'hover'
                    });

                })
            ;


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

            replyid = 0;

            function reply(id) {
                replyid = id;
            }

            function submitTopic() {
                $.post("/action/submitTopicAction.php",
                    {
                        show_at: 0,
                        rpid: replyid,
                        strNewTopic: $("#topicText").val()
                    },
                    function (data, status) {
                        obj = JSON.parse(data);
                        $("#message").html(obj.msg);
                        $('.small.modal')
                            .modal('show')
                        ;
                        if (obj.state === "successful") {
                            $("#topicText").val("");
                            window.location.reload();
                        }
                        $("#btnSubmitTopic").attr("disabled", false);
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
    <div class="ui inverted vertical masthead center aligned segment" id="showpage">
        <div class="ui container">
            <div class="ui large secondary inverted pointing menu">
                <?php showmenu(false); ?>
            </div>
        </div>

        <!-- Following Menu -->
        <div class="ui top fixed hidden menu">
            <div class="ui container">
                <?php showmenu(false); ?>

            </div>
        </div>
        <div class="ui text container">
            <?php if (!User::isLoggedIn()) { ?>
                <h1 class="ui inverted header">
                    一个允许你展示自我<br>寻找朋友的平台
                </h1>
                <h2>你是否有点想要加入呢？</h2>
                <div class="ui huge primary button" onclick="window.location.href='signup.php';">开始<i
                            class="right arrow icon"></i>
                </div>
                <br><br><br><br><br><br><br><br><br><br><br><br>
            <?php } else { ?>
                    <h1 class="ui header" style="color: #ffffff;">欢迎登录，<?php echo User::getLoginUser()->nickname?></h1>
            <?php } ?>
        </div>

    </div>

    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="eight wide column">

                    <h3 class="ui header">我们做到的功能</h3>
                    <ul>
                        <li>结交全国各地的好友，不用担心自己的qq朋友圈不够大</li>
                        <li>自己的站内信，邮件通知</li>
                        <li>好友系统</li>
                        <li>可以上传照片</li>
                        <li>在别人的主页上留言</li>
                        <li>自定义搜索好友</li>
                        <li>发表帖子</li>
                        <li>现已支持qq登录</li>
                    </ul>
                    <h3 class="ui header">我们用心经营</h3>
                    <p>这个网站，完全是由一己之力完成，开发者为此注入了自己全部的心血，
                        只为青少年有一个交友的平台，填补国内再这方面的缺失</p>
                </div>
                <div class="six wide right floated column">
                    <img src="/img/biglogo.png"
                         class="ui large bordered rounded image">
                </div>
            </div>
        </div>
    </div>

    <div class="ui vertical stripe segment">
        <div class="ui text container">
            <h3 class="ui dividing header">最近登录的用户</h3>

            <div class="ui <?php echo(isMobile() ? "two" : "three"); ?> special cards">
                <?php
                $query = "SELECT * FROM `user` ORDER BY `last_login` DESC LIMIT 6";
                $rs = Database::SQLquery($query);

                while ($r = mysqli_fetch_object($rs)) {
                    if (!$r->id)
                        continue;
                    $ur = new User($r);
                    if ($ur->banned)
                        continue;
                    $ur->getFriends();
                    $date = Time::buildDate($ur->signup_date, 1);
                    $diff = Time::diffdate_proximate(time(), $ur->last_login);
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
                ?>
            </div>


            <div class="ui dividing"></div>

            <a href="search.php" class="ui large button">搜索更多好友</a>

            <h3 class="ui dividing header">最受欢迎的用户</h3>
            <div class="ui <?php echo(isMobile() ? "two" : "three"); ?> special cards">
                <?php
                $query = "SELECT * FROM `user` ORDER BY `intr_view_times` DESC LIMIT 7";
                $rs = Database::SQLquery($query);
                require_once ROOT . "MarkdownParser.php";
                $parser = new HyperDown\Parser;
                $parser->_html = false;


                while ($r = mysqli_fetch_object($rs)) {
                    if (!$r->id)
                        continue;
                    $ur = new User($r);
                    if ($ur->banned)
                        continue;
                    $html = cut_str(strip_tags($parser->makeHtml($ur->self_introduction)), 0, 40);
                    $date = Time::buildDate($ur->signup_date, 1);
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
                               <div class='meta'><span class='date'>{$date}</span></div>
                               <div class='description'>
                                    <p>{$ur->province} {$ur->city}</p>
                                    <div>{$html}</div>
                               </div>
                            </div>
                            <div class='extra content'>热度：<div style='color: red'>{$ur->intr_view_times}</div></div>
                               
                        </div>
TAG;
                }
                ?>
            </div>

            <h3 class="ui dividing header">好友推荐</h3>
            <div class="ui <?php echo(isMobile() ? "two" : "three"); ?> special cards">
                <?php
                function mycount($habits1, $habits2)
                {
                    $cnt = 0;
                    foreach ($habits1 as $hb1)
                        foreach ($habits2 as $hb2) {
                            if ($hb1 == $hb2)
                                ++$cnt;
                        }
                    return $cnt;
                }

                function select_user()
                {
                    $query = "SELECT * FROM `user` WHERE `habits` != '' ORDER BY RAND() LIMIT 20;";
                    $rs = Database::SQLquery($query);
                    $users = [];
                    while ($r = mysqli_fetch_object($rs)) {
                        if (!$r->id)
                            continue;
                        $ur = new User($r);
                        if ($ur->banned or $ur->id == User::getLoginUser()->id or User::getLoginUser()->isFriend($ur->id))
                            continue;
                        $lginuser = User::getLoginUser();
                        $lginuser_habits = explode(" ", $lginuser->habits);
                        $ur_habits = explode(" ", $ur->habits);

                        $ur->cnt = mycount($lginuser_habits, $ur_habits);

                        $users[] = $ur;
                    }

                    $users2 = [];
                    $sort = function ($a, $b) {
                        return $a->cnt < $b->cnt;
                    };
                    usort($users, $sort);
                    for ($i = 0; $i < min(6, count($users)); ++$i)
                        $users2[] = $users[$i];
                    return $users2;
                }

                $users = select_user();
                foreach ($users as $ur) {
                    echo <<<TAG
                                <div class="card">
                                    <div class="content">
                                      <div class="header"><img src="/{$ur->img_path}" class="ui avatar image">{$ur->nickname}</div>
                                      <div class="description">{$ur->nickname}是一名喜欢{$ur->habits}的网友。 </div>
                                    </div>
                                    <div class="ui bottom attached button" onclick="addfriend({$ur->id})"><i class="add icon"></i>添加好友</div>
                                </div>
TAG;

                }
                ?>
            </div>
            <h3 class="ui dividing header">热门话题</h3>
            <div class="ui comments">
                <?php
                require_once ROOT . "Topic.php";
                if (isset($_GET['reply']) && !User::isLoggedIn())
                    echo '你需要在登录后查看回复<br />' . "\n";
                $num = 0;
                if (isset($_GET['reply']) && User::isLoggedIn())
                    $topics = Topic::getReplyAndSent();
                else
                    $topics = Topic::getTopics(0);
                foreach ($topics->topics as $topic) {
                    ++$num;
                    $ur = User::getUser("id", $topic->poster);
                    if ($ur->banned)
                        continue;
                    $diff = Time::diffdate_proximate(time(), $topic->postdate);

                    echo <<<TAG

                    
                        <div class='comment'>
                            <div class='avatar'>
                                <img src='/{$ur->img_path}' alt='{$ur->nickname}'>
                            </div>
                            
                            <div class='content'>
                                <a href='profile.php?uid={$ur->id}' class='author'>{$ur->nickname}</a>
                                <div class='metadata'>
                                    <span class='date'>{$diff}</span>
                                </div>
                                <div class='text'>{$topic->text} </div>
                                <div class='actions'>
                                    <a href='javascript:reply({$topic->id})' class='reply'>回复</a>
                                </div>
                            </div>
                        </div>
                    
                    
TAG;
                }
                ?>
                <form class="ui reply form">
                    <div class="field">
                        <textarea id="topicText"></textarea>
                    </div>
                    <div class="ui blue labeled submit icon button" onclick="this.disabled=true; submitTopic()"
                         id="btnSubmitTopic"><i class="icon edit"></i>发表
                    </div>
                </form>
            </div>
        </div>
    </div>





<?php
require_once "footer.php";
