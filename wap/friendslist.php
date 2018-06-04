<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/20
 * Time: 22:36
 */
require_once "wapheader.php";
if (!User::isLoggedIn()) {
    die("你没有登录，请登录");
}
if (isset($_SERVER['HTTP_REFERER']))
    $lastpage = $_SERVER['HTTP_REFERER'];
else
    $lastpage = ".";
?>

<section class="g-flexview">
    <header class="m-navbar" id="navMain">
        <a href="<?php echo $lastpage ?>" class="navbar-item"><i class="back-ico"></i></a>
        <div class="navbar-center"><span class="navbar-title">我的好友</span></div>
    </header>

    <section class="g-scrollview">
        <script language="JavaScript">
            function confirmSubmit() {
                var agree = confirm("你确定要删除这个好友？");
                return !!agree;
            }
        </script>
        <h1 class="m-gridstitle">我的好友</h1>
        <div class="m-grids-2">
            <?php
            $idx = 0;
            $user = User::getLoginUser();
            $user->getFriends();
            foreach ($user->friends as $fd) {
                ++$idx;
                if ($fd->user1id == $user->id)
                    $id = $fd->user2id;
                else
                    $id = $fd->user1id;
                $ur = User::getUser('id', $id);

                ?>
                <script>
                    function <?php echo "sbmt".$idx?>() {
                        ajaxSubmit(friendForm<?php echo $idx ?>, function (jsdata) {
                            obj = JSON.parse(jsdata);
                            alert(obj.msg);
                            if (obj.state === "successful")
                                window.location.reload();
                        });
                    }
                </script>
                <div class="grids-item" style="text-align: center;">
                    <form method="post" action="/action/friendsAction.php" name="friendForm<?php echo $idx ?>">

                        <input type="hidden" name="optype" id="optype">
                        <strong style="font-size: .4rem;">
                            <a title="<?php echo $ur->nickname; ?>"
                               href="profile.php?uid=<?php echo $ur->id ?>"><?php echo $ur->nickname; ?></a>
                            <br><span class="tinyFont"><?php echo $ur->province . " " . $ur->city ?></span>
                        </strong>
                        <div class="profilePhotoSmall">
                            <a href="profile.php?uid=<?php echo $ur->id ?>"><img
                                        class="imgMember96x126"
                                        src="/<?php echo $ur->img_path ?>"
                                        width="96" height="126"
                                        alt="<?php echo $ur->nickname; ?>"
                                        title="<?php echo $ur->nickname; ?>"
                                        border="0" style="margin:0 auto"></a>
                        </div>
                        <br>
                        <?php
                        switch ($fd->state) {
                            case 0:
                                echo "<div class=\"tinyFont tightLineHeight\">好友正在等待批准</div>";
                                break;
                            case 1:
                                echo "<div class=\"tinyFont tightLineHeight\">已经是好友了</div>";
                                break;
                        }
                        if ($fd->state == 1) {
                            echo "<div class=\"tinyFont tightLineHeight\">成为好友自<br>$fd->accept_date</div>";
                            echo "<div style='margin: .2rem .2rem .2rem .2rem'>
                                    <input type=\"button\" id=\"unfriend{$idx}\" value=\"删除好友\" class=\"btn-block btn-danger\" title=\"删除好友\" 
                                    onclick=\"if(confirmSubmit()){optype.value='unfriend';this.disabled=true;sbmt{$idx}();}\"></div>";
                        } else if ($fd->state == 0) {
                            echo "<div class=\"tinyFont tightLineHeight\">请求申请好友自<br>$fd->applicate_date</div>";
                            if ($fd->user1id == User::getLoginUser()->id) {
                                echo "<div>
                                        <input type=\"button\" id=\"accept{$idx}\" value=\"接受好友\" class=\"btn-block btn-primary\" title=\"接受好友\" style='width: 50%; float: left'
                                        onclick=\"optype.value='accept';this.disabled=true;sbmt{$idx}();\"></div>";
                                echo "<div>
                                        <input type=\"button\" id=\"refuse{$idx}\" value=\"拒绝好友\" class=\"btn-block btn-danger\" title=\"拒绝好友\" style='width: 50%; float: left'
                                        onclick=\"optype.value='refuse';this.disabled=true;sbmt{$idx}();\"></div>";

                            } else {
                                echo "<div style='margin: .2rem .2rem .2rem .2rem'>
                                    <input type=\"button\" id=\"cancel{$idx}\" value=\"取消好友\" class=\"btn-block btn-primary\" title=\"取消好友\" 
                                    onclick=\"optype.value='cancel';this.disabled=true;sbmt{$idx}();\"></div>";
                            }
                        }
                        ?>
                        <input type="hidden" name="user1id" value="<?php echo $ur->id ?>">
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
</section>

<?php
require_once "wapfooter.php";
?>
