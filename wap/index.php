<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/19
 * Time: 19:36
 */
require_once "wapheader.php";
require_once ROOT . "/MarkdownParser.php";
?>

    <section class="g-flexview">
        <div id="navMain" style="display: none"></div>
        <div class="g-scrollview">

            <h1 class="demo-pagetitle">卓信社交</h1>
            <h2 class="demo-detail-title">一款专门为全国青少年准备的交友平台</h2>
            <div class="demo-detail-text">
                <p>你可以在这里，见识各种各样的青少年。各种功能正在陆续添加中，全国各地的青少年正在陆续加入，敬请期待。</p>
                <p>已有 <?php $key = 'count(*)';
                    print(mysqli_fetch_object(Database::SQLquery("SELECT count(*) FROM `user` WHERE `id` > 0"))->$key); ?>
                    位用户注册了帐号</p>
                <?php
                if (!User::isLoggedIn()) {
                    ?>
                    <p>登录后开启更多功能</p>
                    <?php
                }
                ?>
            </div>
            <div class="m-grids-3">
                <?php
                if (!User::isLoggedIn()) {
                    ?>
                    <a class="grids-item" href="/login.php">
                        <div class="grids-icon">
                            <i class="demo-icons-dialog"></i>
                        </div>
                        <div class="grids-txt">登录</div>
                    </a>
                    <a class="grids-item" href="/wap/signup.php">
                        <div class="grids-icon">
                            <i class="demo-icons-dialog"></i>
                        </div>
                        <div class="grids-txt">注册</div>
                    </a>
                <?php } else { ?>

                    <a class="grids-item" href="/logout.php">
                        <div class="grids-icon">
                            <i class="demo-icons-dialog"></i>
                        </div>
                        <div class="grids-txt">登出</div>
                    </a>
                    <a class="grids-item" href="/wap/profile.php?uid=<?php echo User::getLoginUser()->id; ?>">
                        <div class="grids-icon">
                            <i class="demo-icons-dialog"></i>
                        </div>
                        <div class="grids-txt">我的资料</div>
                    </a>
                    <a class="grids-item" href="/wap/friendslist.php">
                        <div class="grids-icon">
                            <i class="demo-icons-dialog"></i>
                        </div>
                        <div class="grids-txt">我的朋友</div>
                    </a>
                    <?php
                }
                ?>
                <a class="grids-item" href="/wap/contactus.php">
                    <div class="grids-icon">
                        <i class="demo-icons-button"></i>
                    </div>
                    <div class="grids-txt">联系我们</div>
                </a>
                <a class="grids-item" href="/wap/search.php">
                    <div class="grids-icon">
                        <i class="demo-icons-button"></i>
                    </div>
                    <div class="grids-txt">搜索用户</div>
                </a>
            </div>


            <div class="m-grids-1">

                <div class="grids-item">
                    <h2 class="m-gridstitle">
                        最近登录
                    </h2>
                    <?php
                    $query = "SELECT * FROM `user` ORDER BY `last_login` DESC LIMIT 12";
                    $rs = Database::SQLquery($query);

                    echo "<div class=\"m-grids-3\">\n";
                    if (mysqli_num_rows($rs))
                        while ($r = mysqli_fetch_object($rs)) {

                            if (!$r->id)
                                continue;
                            $ur = new User($r);
                            if ($ur->banned)
                                continue;
                            echo "<div class=\"grids-item\" style='height: 6rem; padding:.2rem .2rem .2rem .2rem'>";
                            if (User::isLoggedIn())
                                echo "<a href=\"profile.php?uid={$ur->id}\">\n";
                            echo "<div class=\"homepagePhotos\" style=\"margin:0 auto;\"><strong>{$ur->nickname}</strong></div>";
                            echo "<img width=\"96\" height=\"126\" src=\"/{$ur->img_path}\" class=\"imgMember96x126\"
                                        alt=\"{$ur->nickname}\" title=\"{$ur->nickname}\">";;
                            echo "<div id=\"counter0\" class=\"homepageTexts\" >{$ur->province} {$ur->city}<br />上次登录<br /> {$ur->last_login}</div>";
                            if (User::isLoggedIn())
                                echo "</a>\n";
                            echo "</div>\n";
                        }
                    echo "</div>\n";
                    ?>
                </div>

                <div class="grids-item">

                    <?php
                    if (User::isLoggedIn()) {
                        ?>
                        <div class="m-gridstitle">好友推荐</div>
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
                            $query = "SELECT * FROM `user` ORDER BY RAND() LIMIT 20;";
                            $rs = Database::SQLquery($query);
                            $users = [];
                            if (mysqli_num_rows($rs))
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
                        echo "<div class='m-grids-3'>\n";
                        foreach ($users as $ur) {
                            echo "<div class=\"grids-item\" style='padding: .2rem .2rem .2rem .2rem; height: 6rem'>";
                            if (User::isLoggedIn())
                                echo "<a href=\"profile.php?uid={$ur->id}\">\n";
                            echo "<div class=\"homepagePhotos\" style=\"margin:10px 0 0 0;\"><h3 style='color: red'>{$ur->nickname}</h3></div>";
                            echo "<img width=\"96\" height=\"126\" src=\"/{$ur->img_path}\" class=\"imgMember96x126\" style='margin: 0 auto'
                                            alt=\"{$ur->nickname}\" title=\"{$ur->nickname}\">";
                            $hobbies = cut_str($ur->habits, 0, 30);

                            echo "<div class='homepageTexts'>{$ur->province} {$ur->city}<br>$hobbies($ur->cnt)</div>";

                            if (User::isLoggedIn())
                                echo "</a>\n";
                            echo "</div>";
                        }
                        ?>
                        <div class="lightGrayColor"
                             style="text-indent: 10px;text-align: right;margin-top: 235px;margin-right: 20px;"><a
                                    href="/">换一批</a></div>

                        <?php
                        echo "</div>\n";

                    }
                    ?>
                </div>

                <div class="grids-item">
                    <h2 class="m-gridstitle">
                        热度明星
                    </h2>

                    <?php
                    echo "<div class=\"m-grids-3\"'>\n";
                    $query = "SELECT * FROM `user` ORDER BY `intr_view_times` DESC LIMIT 6";
                    $rs = Database::SQLquery($query);
                    $parser = new HyperDown\Parser;
                    if (mysqli_num_rows($rs))
                        while ($r = mysqli_fetch_object($rs)) {
                            if (!$r->id)
                                continue;
                            $ur = new User($r);
                            if ($ur->banned)
                                continue;
                            if (User::isLoggedIn())
                                echo "<a href=\"profile.php?uid={$ur->id}\">\n";
                            echo "<div class=\"grids-item\" style=\"height: 6rem; padding: .2rem .2rem .2rem .2rem;\">";
                            echo "<div class=\"homepagePhotos\" style=\"margin:10px 0 0;\"><h3 style='color: red'>{$ur->nickname}</h3></div>";
                            echo "<img width=\"96\" height=\"126\" src=\"/{$ur->img_path}\" class=\"imgMember96x126\" style='margin: 0 auto' 
                                    alt=\"{$ur->nickname}\" title=\"{$ur->nickname}\">";
                            $parser = new HyperDown\Parser;
                            $html = strip_tags($parser->makeHtml(cut_str($ur->self_introduction, 0, 24)));
                            echo "<div id=\"counter0\" class=\"homepageTexts\">热度 {$ur->intr_view_times}<br />  {$html}</div>";
                            if (User::isLoggedIn())
                                echo "</a>\n";
                            echo "</div>";
                        }
                    echo "</div>";
                    ?>

                </div>

                <div class="grids-item">
                    <h2 class="m-gridstitle">
                        随笔话题
                    </h2>
                    <div class="m-grids-1" style="padding-right: .24rem">
                        <?php
                        require_once ROOT . "/Topic.php";
                        if (isset($_GET['reply']) && !User::isLoggedIn())
                            echo '你需要在登录后查看回复<br />' . "\n";
                        $num = 0;
                        if (isset($_GET['reply']) && User::isLoggedIn())
                            $topics = Topic::getReplyAndSent();
                        else
                            $topics = Topic::getTopics(0);
                        echo '<div class="m-grids-1">' . "\n";
                        foreach ($topics->topics as $topic) {
                            ++$num;
                            $ur = User::getUser("id", $topic->poster);
                            if ($ur->banned)
                                continue;

                            echo '<div class="m-grids-3" >' . "\n";
                            echo '    <div class="commentsBorder">' . "\n";

                            echo '        <div class="grids-item" style="width: 25%">' . "\n";
                            echo '            <a href="profile.php?uid=' . $ur->id . '">' . "\n";
                            echo '                <img width="64" height="64" title="' . $ur->nickname . '" alt="' . $ur->nickname . '" src="/' . $ur->img_path . '" border="0" style="margin: 0 auto">' . "\n";
                            echo '            </a>' . "\n";
                            echo '        </div>' . "\n";

                            echo '        <div class="grids-item" style="width: 65%;">' . "\n";
                            echo '            <a href="profile.php?uid=' . $ur->id . '"> ' . $ur->nickname . ':</a> <span style="float: right">' . $topic->postdate . '</span>' . "\n";
                            echo '            <br />' . "\n";
                            $reply = "";
                            if ($topic->reply_to)
                                $reply = "回复给 " . User::getUser('id', $topic->reply_to)->nickname . ":<br />";
                            echo '            <span class="darkGrayColor" style="margin-top:18px">' . $reply . htmlspecialchars(urldecode($topic->text)) . '</span>' . "\n";
                            echo '        </div>' . "\n";

                            echo '        <div class="grids-item" style="width: 10%; text-align: right">' . "\n";

                            echo '            <script>' . "\n";
                            echo '              function reply' . $num . '(){' . "\n";
                            echo '              $("#reply_to_id").val(' . User::getUser('id', $topic->poster)->id . ');' . "\n";
                            echo '              $("#reply_to").html("回复给:' . $ur->nickname . '").attr("style", "");' . "\n";
                            echo '              $("#cancel_reply").attr("style", "");' . "\n";
                            echo '              }' . "\n";
                            echo '            </script>' . "\n";
                            echo '            <a href="javascript:reply' . $num . '();"><div class="btn btn-primary" style="width: 100%; height: 100%; ">回复</div></a>  ' . "\n";
                            echo '        </div>' . "\n";

                            echo '        <div class="clearBoth"></div>' . "\n";
                            echo '    </div>' . "\n";
                            echo '</div>' . "\n";
                        }
                        echo "</div>\n";
                        ?>
                        <br/>
                        <h2 class="m-gridstitle">编辑</h2>
                        <script>
                            function CheckLen(TheForm) {
                                MsgLen = TheForm.strNewTopic.value.length;
                                if (MsgLen > 150) {
                                    TheForm.strNewTopic.value = TheForm.strNewTopic.value.substring(0, 150);
                                    CharsLeft = 0;
                                    $("#sendtpbt").attr("disabled", true);
                                } else {
                                    CharsLeft = 150 - MsgLen;
                                    $("#sendtpbt").attr("disabled", false);
                                }
                                TheForm.CharsLeft.value = CharsLeft;
                            }
                        </script>
                        <br/>

                        <script language="javascript" type="text/javascript">
                            function sendtp() {
                                ajaxSubmit(commentForm, function (data) {
                                    window.alert(data);
                                    $('#strNewTopic').val("");
                                    $("#sendtpbt").html("发送").attr("disabled", false);
                                    window.location.reload();
                                });
                            }
                        </script>

                        <form action="/action/submitTopicAction.php" method="post" id="commentForm" name="commentForm">
                            <input type="hidden" name="show_at" value="0"/>
                            <input id="reply_to_id" type="hidden" name="rpid" value="0"/>
                            <textarea cols="40" rows="5" name="strNewTopic" id="strNewTopic"
                                      onfocus="CheckLen(form)" onblur="CheckLen(form)"
                                      onkeydown="CheckLen(form)" onkeyup="CheckLen(form)"
                                      style="width: 550px;"></textarea>
                            <br/>
                            <label id="reply_to" style="display: none;">回复给：</label>
                            <script>
                                function reply0() {
                                    $("#reply_to_id").val(0);
                                    $("#reply_to").html("回复给:").attr("style", "display:none");
                                    $("#cancel_reply").attr("style", "display:none");
                                }
                            </script>
                            <a href="javascript:reply0();" id="cancel_reply" style="display: none">取消</a>
                            <div class="buttons to_the_right" style="float: right">
                                <input type="button" id="sendtpbt" name='sendtpbt' class="btn btn-primary"
                                       title="发送"
                                       value="发送"
                                       onclick="this.disabled=true;this.html='working...';sendtp();"/>

                            </div>
                            <div style="float: right">
                                剩余<input type="text" name="CharsLeft" value="150" size="3"
                                         style="width:35px;padding-right:5px;text-align:right;background:#ccc;color:#fff;border:0px solid #fff;"
                                         title="">字<br/>
                            </div>

                        </form>
                    </div>
                </div>


            </div>

        </div>

    </section>

<?

require_once "wapfooter.php";

?>