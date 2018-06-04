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
        <title>我的好友 —— 卓信社交——专门为青少年准备的社交平台</title>
        <link rel="stylesheet" type="text/css" href="/scripts/css/semantic.min.css">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>
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


            function action(id, method) {
                $.post("/action/friendsAction.php",
                    {
                        user1id: id,
                        optype: method
                    },
                    function (data, status) {
                        obj = JSON.parse(data);
                        $("#message").html(obj.msg);
                        $('.small.modal')
                            .modal('show')
                        ;
                        if (obj.state === "successful") {
                            setTimeout("window.location.reload();", 2000);

                        }
                    })
                ;

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
    <div class="ui raised very padded text container segment">
        <h1 class="ui header">我的好友</h1>
        <div class="ui cards">
            <?php
            if (User::isLoggedIn()) {
                $user = User::getLoginUser();
                $user->getFriends();
                echo "<div class='ui label'>我有{$user->friendsnum}位好友</div>";
                foreach ($user->friends as $fd) {
                    if ($fd->user1id == $user->id)
                        $id = $fd->user2id;
                    else
                        $id = $fd->user1id;
                    require_once ROOT . "MarkdownParser.php";
                    $ur = User::getUser('id', $id);
                    $parser = new \HyperDown\Parser();
                    $html = $parser->makeHtml(cut_str($ur->self_introduction, 0, 30));
                    $html = (trim($html) === "" ? "发送了好友申请" : $html);

                    switch ($fd->state)
                    {
                        case 0:
                            if ($fd->user1id == $user->id)
                                $button = <<<TAG
                            
                            <div class="ui two buttons">
                                <div class="ui basic green button" onclick="action($id, 'accept')">接受</div>
                                <div class="ui basic red button"  onclick="action($id, 'refuse')">拒绝</div>
                            </div>
TAG;
                            else
                                $button = <<<TAG
                            
                            <div class="ui one buttons">
                                <div class="ui basic red button"  onclick="action($id, 'cancel')">取消</div>
                            </div>
TAG;
                            break;
                        case 1:
                            $button = <<<TAG
                            
                            <div class="ui one buttons">
                                <div class="ui basic red button" onclick="action($id, 'unfriend')">解除好友</div>
                            </div>
TAG;
                            break;
                        default:
                            $button = "";
                    }
                    echo <<<TAG
                <div class="card">
                    <div class="content">
                        <img class="right floated ui avatar image" src="/{$ur->img_path}">
                        <div class="header">
                            <a href="profile.php?uid={$ur->id}">
                                {$ur->nickname}
                            </a>
                            
                        </div>
                        <div class="meta">
                            {$ur->age} 岁 {$ur->province} {$ur->city}
                        </div>
                        <div class="description">
                            $html
                        </div>
                    </div>
                    <div class="extra content">
                        $button
                    </div>
                </div>
TAG;
                }
            } else {
                 echo <<<TAG
                    <div class="ui info message" id="tip">
                        您还没有登录，请登录后查看您的好友。                
                                 
                    </div>
TAG;

            }
            ?>
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
