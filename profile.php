<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/19
 * Time: 19:36
 */
require_once "header.php";
require_once ROOT . "/MarkdownParser.php";
if (!isset($_GET['uid']))
    die("你不能直接访问这个页面");

$userid = (int)$_GET['uid'];
$user = User::getUser('id', $userid);

if ($user->banned or !$user->valid()) {
    if (isset($_SERVER['HTTP_REFERER']))
        header("location: {$_SERVER['HTTP_REFERER']}");
    else
        header("location: .");
}

Database::update("user", "intr_view_times", "' + `intr_view_times` + 1 + '", "id=" . $user->id);

?>

    <!DOCTYPE html>
    <html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properties -->
        <title><?php echo $user->nickname ?>的空间 - 卓信社交</title>

        <link rel="stylesheet" type="text/css" href="/scripts/css/semantic.min.css">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>

        <script src="/scripts/jquery-3.3.1.min.js"></script>
        <script src="/scripts/semantic.min.js"></script>

        <script>
            $(document)
                .ready(function () {

                    // create sidebar and attach to menu open
                    $('.ui.sidebar')
                        .sidebar('attach events', '.toc.item')
                    ;

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

            function submitCommit() {
                $.post("/action/submitCommitAction.php",
                    {
                        uid: <?php echo $user->id?>,
                        strNewComment: $("#commitText").val()
                    },
                    function (data, status) {
                        obj = JSON.parse(data);
                        $("#message").html(obj.msg);
                        $('.small.modal')
                            .modal('show')
                        ;
                        if (obj.state === "successful") {
                            $("#commitText").val("");
                            window.location.reload();
                        }
                        $("#btnSubmitCommit").attr("disabled", false);
                    });
            }
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
    <div class="ui img modal">
        <img src="/<?php echo $user->img_path ?>" class="ui centered image" style="margin: 0 auto">
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
        <br><br><br><br><br>

        <?php
        $parser = new \HyperDown\Parser();
        $html = $parser->makeHtml($user->self_introduction);
        $gender = array(
            "m" => "帅哥",
            "f" => "美女",
            "s" => "网友",
            "" => "网友"
        );
        $habits = "<p>爱好 ";
        $splt = explode(" ", $user->habits);
        foreach ($splt as $s) {
            if ($s)
                $habits .= '<a class="ui img label">' . $s . '</a>';
        }
        $habits .= "</p>";
        $signup = Time::diffdate_proximate($user->signup_date);

        $user->getFriends();
        $friendscards = "";
        foreach ($user->friends as $fd) {
            if ($fd->user1id == $user->id)
                $id = $fd->user2id;
            else
                $id = $fd->user1id;
            $f = User::getUser('id', $id);
            if ($f->banned)
                continue;
            $friendscards .= <<<TAG
            <div class="card">
                <div class="content">
                
                  <div class="header"><a href="profile.php?uid={$f->id}">{$f->nickname}</a> <img src="/{$f->img_path}" class="ui right floated avatar image"></div>
                  <div class="description">
                    <p>{$f->age}岁 $f->province $f->city</p>
                    <p>$f->habits</p>
                  </div>
                </div>
            </div>
            
            
TAG;
        }
        $user->getCommits();
        $commits = "";
        foreach ($user->commits as $commit) {
            $ur = User::getUser("id", $commit->user2id);
            if ($ur->banned)
                continue;

            $diff = Time::diffdate_proximate($commit->date);
            $commits .= <<<TAG
            <div class='comment'>
                <div class='avatar'>
                    <img src='/{$ur->img_path}' alt='{$ur->nickname}'>
                </div>
                
                <div class='content'>
                    <a href='profile.php?uid={$ur->id}' class='author'>{$ur->nickname}</a>
                    <div class='metadata'>
                        <span class='date'>{$diff}</span>
                    </div>
                    <div class='text'>{$commit->text} </div>
                    <div class="action"></div>
                </div>
            </div>
TAG;

        }
        echo <<<TAG
        <div class="ui two column stackable grid container">
            <h1 class="ui header">{$user->nickname}</h1>
            <a href="javascript:addfriend({$user->id});" class="ui button primary" style="height: 2.5rem;"><i class="add user icon"></i> 加为好友</a>
            <a href="myMessage.php?uid={$userid}" class="ui button primary" style="height: 2.5rem;"><i class="comment alternate outline icon"></i> 发送消息</a>
            <div class="row">
                <div class="column" style="width: 25%">
              
                    <i class="huge icons">
                      <i><img src="/{$user->img_path}" class="ui small bordered rounded image" id="user_avatar" ></i>
                      <i class="corner zoom icon" onclick="$('.img.modal').modal('show');"></i>
                    </i>
                    
                </div>
                <div class="column" style="width: 75%">
                    <p>注册于{$signup}</p>
                    <p>{$user->nickname}是一个住在{$user->province} {$user->city}的{$user->age}岁{$gender[$user->gender]}</p>
                    希望与年龄在{$user->wanna_down}岁和{$user->wanna_up}岁之间的人交朋友
                    {$habits}
                    <p>被浏览{$user->intr_view_times}次</p>
                </div>
            </div>
        </div>
        <br>
        <div class="ui horizontal divider">Self Introduction</div>
        
            <div class="content">
                $html
            </div>
        <br>
        <div class="ui horizontal divider">Friends</div>
        
            <div class="content">
                <div class="three ui stackable cards">
                    $friendscards
                </div>
            </div>
        <br>
        <div class="ui horizontal divider">Commits</div>
        
            <div class="ui comments">
                $commits
            </div>
        
            <div class="content">
                <form class="ui reply form">
                    <div class="field">
                        <textarea id="commitText"></textarea>
                    </div>
                    <div class="ui blue labeled submit icon button" onclick="this.disabled=true; submitCommit()" id="btnSubmitCommit"><i class="icon edit"></i>发表
                    </div>
                </form>
            
            </div>
        </div>

TAG;
        ?>


<?php
require_once "footer.php";