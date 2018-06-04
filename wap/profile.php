<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/20
 * Time: 22:36
 */
require_once "wapheader.php";
if (!isset($_GET['uid']))
    die("你不能直接访问这个页面");

$userid = (int)$_GET['uid'];
$user = User::getUser('id', $userid);
if ($user->banned)
    die("这个用户被封禁了，不能访问");
if (isset($_SERVER['HTTP_REFERER']))
    $lastpage = $_SERVER['HTTP_REFERER'];
else
    $lastpage = ".";

require_once ROOT . "/MarkdownParser.php";

$parser = new HyperDown\Parser;
$html = $parser->makeHtml($user->self_introduction);

echo <<<TAG

<section class="g-flexview">
    <header class="m-navbar" id="navMain">
        <a href="$lastpage" class="navbar-item"><i class="back-ico"></i></a>
        <div class="navbar-center"><span class="navbar-title">{$user->nickname}的首页</span></div>
    </header>

    <section class="g-scrollview">
        <div class="m-grids-1">
                <div class="m-grids-2">
                    <div class="grids-item" style="width: auto;height: 3rem;padding-left: .24rem">
                         <a href="profile.php?uid=$user->id">
                            <img class="imgMember96x126" src="/$user->img_path" width="96" style="margin: 0 auto"
                                    height="126" alt="$user->nickname" title="$user->nickname" border="0">
                         </a>
                    </div>
                    
                    <div class="grids-item" style="margin-left: .2rem">
                        <h2 style="padding-right:10px; font-size: .6rem">
                             <span class="blueColor noUnderline">
                                 <a href="profile.php?uid=$user->id">$user->nickname</a>
                             </span>
                        </h2>
                        
                        <h3 style="white-space:nowrap; ">
                              $user->province &nbsp; $user->city
                        </h3>
                        <strong style="white-space:nowrap; ">$user->age 岁</strong>
                        <br>
                        <span class="tinyFont">希望与年龄在 $user->wanna_down 岁和 $user->wanna_up
                            岁之间的人交朋友</span>
TAG;

if (User::isLoggedIn()) {
    if ($user->id == User::getLoginUser()->id) {
        ?>
        <div id="ajaxAddFriendDiv" style="font-size: .1rem">你不能加自己为好友</div>
        <?php
    } else if (!$user->isFriend(User::getLoginUser()->id)) {
        ?>
        <script>
            function sm() {
                ajaxSubmit(frm, function (rawjs) {
                    obj = JSON.parse(rawjs);
                    alert(obj.msg);
                });
            }
        </script>
        <div style="display: none">
            <form action="/action/friendsAction.php" id="frm" method="post">
                <input type="hidden" name="optype" value="apply"/>
                <input type="hidden" name="user1id"
                       value="<?php echo $user->id ?>"/>
            </form>
        </div>
        <div id="ajaxAddFriendDiv">
            <a href="javascript:sm();">添加ta为好友</a>
        </div>
        <?php
    } else {
        ?>
        <div id="ajaxAddFriendDiv">您和ta已经是好友或好友申请已经发送</div>
        <?php

    }
} else {
    ?>
    <div id="ajaxAddFriendDiv">您要在登录才可添加ta为好友</div>
    <?php
}
echo <<<TAG
                        <div class="grayBar">
                            <div class="grayBarLastLogin tinyFont darkGrayColor">登录于 $user->last_login</div>
                            <div class="grayBarMemberSince tinyFont darkGrayColor">注册于 $user->signup_date </div>
                        </div>
                    </div>
            </div>
            <div class="grids-item">
                <div class="demo-detail-text">$html</div>
            </div>
            <div class="demo-small-pitch"></div>
TAG;
?>
<div class="grids-item">
    <h2 class="m-gridstitle">用户留言</h2>
    <div class="m-grids-1" style="padding-right: .24rem">
        <?php
        $user->getCommits();
        $num = 0;
        foreach ($user->commits as $commit) {
            ++$num;
            $ur = User::getUser("id", $commit->user2id);
            if ($ur->banned)
                continue;
            echo '<div class="m-grids-2" id="ajaxNextCommentsDiv">';
            echo '    <div class="grids-item" style="width: 25%;">';
            echo '        <div class="commentsPicture">';
            echo '            <a href="profile.php?uid=' . $ur->id . '">';
            echo '                <img width="64" height="64" title="' . $ur->nickname . '" alt="' . $ur->nickname . '" src="/' . $ur->img_path . '" border="0" style="margin: 0 auto">';
            echo '            </a>';
            echo '        </div>';
            echo '    </div>';
            echo '    <div class="grids-item" style="width: 75%;">';
            echo '        <div class="commentsText">';
            echo '            <a href="profile.php?uid=' . $ur->id . '"> ' . $ur->nickname . ':</a><span style="float: right">' . $commit->date . '</span>';
            echo '            <br />';
            echo '            <span class="darkGrayColor">' . htmlspecialchars(urldecode($commit->text)) . '</span>';
            echo '        </div>';
            echo '        <div class="to_the_right" style="text-align:right;">';
            echo '        <form id="rmfm' . $num . '" name="rmfm' . $num . '" action="/action/submitCommitAction.php?remove=true" method="post"><input type="hidden" name="cmtid" value="' . $commit->id . '"/></form>';
            echo '        <script>function removecmt' . $num . '(){ajaxSubmit(rmfm' . $num . ', function(data) { ';
            echo '                    window.alert();';
            echo '                    window.location.reload();';
            echo '        });}</script>';
            echo '        <div class="darkGrayColor"><a href="javascript:removecmt' . $num . '();">删除</a></div>  ';
            echo '        </div>';
            echo '        <div class="clearBoth">';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
        ?>
    </div>
    <h2 class="m-gridstitle">编辑留言</h2>

    <script>
        function CheckLen(TheForm) {
            MsgLen = TheForm.strNewComment.value.length;
            if (MsgLen > 150) {
                TheForm.strNewComment.value = TheForm.strNewComment.value.substring(0, 150);
                $("#sendcmbt").attr("disabled", true);
                CharsLeft = 0;
            } else {
                $("#sendcmbt").attr("disabled", false);
                CharsLeft = 150 - MsgLen;
            }
            TheForm.CharsLeft.value = CharsLeft;
        }
    </script>
    <script language="javascript" type="text/javascript">
        function sendcmt() {
            ajaxSubmit(commentForm, function (data) {
                window.alert(JSON.parse(data).msg);
                $('#strNewComment').val("");
                $("#sendcmbt").html("发送").attr("disabled", false);
            });
        }
    </script>

    <form action="/action/submitCommitAction.php" method="post" name="commentForm" style="margin: .2rem .2rem .2rem .2rem">
        <input type="hidden" name="uid" value="<?php echo $user->id ?>"/>

        <textarea rows="5" name="strNewComment" id="strNewComment"
                  onfocus="CheckLen(form)" onblur="CheckLen(form)"
                  onkeydown="CheckLen(form)" onkeyup="CheckLen(form)" style="width: 100%;touch-action: none;"></textarea>
        <div class="m-cell" style="float: right; padding-right: .2rem">
            剩余
            <input type="text" name="CharsLeft" value="150" size="3"
                   style="width:35px;padding-right:5px;text-align:right;background:#ccc;color:#fff;border:0 solid #fff;">
            字
        </div>
        <div style="padding: 5px 5px 5px 5px">
            <button type="button" id='sendcmbt' name='sendcmbt' class="btn-block btn-primary"
                    onclick="this.disabled=true;this.html='working...';sendcmt();">发送
            </button>
        </div>
    </form>
</div>

<div class="grids-item">
    <h2 class="m-gridstitle"><?php echo $user->nickname; ?>有<?php $user->getFriends();
        echo $user->friendsnum; ?>位好友</h2>
    <div id="ajaxNextFriendsDiv" style="width: 2rem; margin: .3rem .3rem .3rem .3rem">
        <?php

        foreach ($user->friends as $each) {
            if ($each->state != 1)
                continue;
            if ($each->user1id != $user->id)
                $id = $each->user1id;
            else
                $id = $each->user2id;

            $eaur = User::getUser("id", $id);

            echo '<div class="boxFriendsEachWrap">';
            echo '    <div class="boxFriendsPicWrap">';
            echo '        <a href="profile.php?uid=' . $eaur->id . '">';
            echo '            <img width="64" height="64" title="' . $eaur->nickname . '" alt="' . $eaur->nickname . '" src="/' . $eaur->img_path . '" border="0" " style="margin: 0 auto">';
            echo '        </a>';
            echo '    </div>';
            echo '    <div class="boxFriendTextWrap smallFont" style="text-align: center">';
            echo '        <a href="profile.php?uid=' . $eaur->id . '">' . $eaur->nickname . '</a>';
            echo '    </div>';
            echo '</div>';
        }

        ?>
        <div class="clearBoth">
        </div>
    </div>
    <div class="clearBoth">
    </div>
</div>
<div class="grids-item">
    <script language="javascript" type="text/javascript">
        function sendmsg() {
            ajaxSubmit(messageform, function (data) {
                obj = JSON.parse(data);
                window.alert(obj.msg);
                if (obj.state === "successful")
                $('#messMessage').val("");
                $("#sendbt").val("发送").attr("disabled", false);
            });
        }
    </script>
    <form id="messageform" action="/action/sendMessageAction.php"
          method="post" name="messageForm">
        <h2 class="m-gridstitle"><label for="messMessage">发送消息</label></h2>
        <div class="m-cell">
            <div class="cell-item" style="padding-left: 0">
                <div style="width: auto; height: 20em; margin: .2rem .2rem .2rem .2rem">
                    <textarea cols="50" name="messMessage" id="messMessage" style="
                    width: 100%; /*自动适应父布局宽度*/
                    height: 100%;
                    overflow: auto;
                    word-break: break-all; "></textarea>

                </div>
            </div>
            <input type="hidden" name="uid" value="<?php echo $user->id ?>"/>

            <div class="cell-item" style="padding-right: .24rem; touch-action: none;">
                <input type="button" id='sendbt' name='sendbt' class="btn-block btn-primary" title="发送"
                       value="发送"
                       onclick="this.disabled=true;this.html='working...';sendmsg();"/>

            </div>
        </div>
    </form>
</div>
<?php
echo "
        </div>
        
    </section>
</section>

";
require_once "wapfooter.php";
?>
