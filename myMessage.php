<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/29
 * Time: 9:46
 */
require_once "header.php";

$user = User::getLoginUser();
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <title>我的消息 卓信</title>
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
    <div class="ui raised very padded text container segment">

        <img src="/img/biglogo.png" alt="卓信" class="ui centered medium image">
        <h1 class="ui dividing header">
            我的消息
        </h1>
        <?php
        if (isset($_GET['uid']))
        {
            $with_user = User::getUser('id', (int)$_GET['uid']);
            echo "<h4 class='ui header'><img src='/{$with_user->img_path}' class='ui avatar image' onclick='window.location.href=\"profile.php?uid={$with_user->id}\"'> 与 {$user->nickname} 的消息<img src='/{$user->img_path}' class='ui right floated avatar image'></h4>";

        }
        ?>
        <div class="ui large feed">
            <?php

                $messages = $user->getMessages();
                quicksort($messages, 0, count($messages) - 1, "date");
                $messages = array_reverse($messages);
                foreach ($messages as $m) {

                    $diff = Time::diffdate_proximate($m->date);
                    $text = $m->text;
                    $poster = User::getUser("id", $m->user2id);
                    $receiver = User::getUser("id", $m->user1id);
                    if (isset($_GET['uid']))
                    {
                        if ($poster->id != $_GET['uid'] or $receiver->id != $_GET['uid'])
                            continue;
                    }
                    else
                        $text = cut_str($m->text, 0, 80);
                    if ($poster->id == $user->id)
                        echo <<<TAG
                    
                        <div class="ui raised card" onclick="window.location.href = 'myMessage.php?uid=$poster->id'" style="width: 100%;">
                          <div class="content">
                            <div class="header">
                                给{$receiver->nickname}发送了一条消息
                            </div>
                            <div class="meta">
                                <span class="left floated time">{$diff}</span>
                            </div>
                            <div class="description">
                                $text
                            </div>
                          </div>
                        
                          <div class="extra content">
                            <div class="right floated author"><img class="ui avatar image" src="/{$poster->img_path}">{$poster->nickname}</div>
                          </div>
                       </div>
TAG;
                    else
                        echo <<<TAG
                    
                        <div class="ui raised card" onclick="window.location.href = 'myMessage.php?uid=$poster->id'" style="width: 100%;">
                          <div class="content">
                            <div class="header">
                                {$poster->nickname} 发送了一条消息
                            </div>
                            <div class="meta">
                                <span class="left floated time">{$diff}</span>
                            </div>
                            <div class="description">
                                $text
                            </div>
                          </div>
                        
                          <div class="extra content">
                            <div class="left floated author"><img class="ui avatar image" src="/{$poster->img_path}">{$poster->nickname}</div>
                          </div>
                       </div>
                            
TAG;

            }
            if (isset($_GET['uid']))
            {
                $uid = (int)$_GET['uid'];
                echo <<<TAG
                <form action="/action/sendMessageAction.php" method="post" class="ui form" id="protext">
                    <input type="hidden" name="uid" value="{$uid}">
                    <textarea name="messMessage"></textarea>
                    <input type="button" value="发送" class="ui right floated primary button" id="sendbt" onclick="this.disabled = true; ajaxSubmit(protext, function(data) {
                      obj = JSON.parse(data);myalert(obj.msg);$('#sendbt').attr('disabled', false);
                    })">
                </form>
                
TAG;

            }
            ?>
        </div>
</body>
</html>
