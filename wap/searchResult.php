<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/20
 * Time: 20:05
 */
require_once "wapheader.php";
?>
<section class="g-flexview">

    <header class="m-navbar" id="navMain">
        <a href="index.php" class="navbar-item"><i class="back-ico"></i></a>
        <div class="navbar-center"><span class="navbar-title">搜索用户</span></div>
    </header>
    <header class="g-scrollview">
        <?php
        $condlist = ['strName', 'wanna_down', 'wanna_up', 'cb_male', 'cb_fmale', 'strProvince', 'strCity', 'strHobby'];
        $cond = [];
        foreach ($condlist as $item) {
            if (isset($_GET[$item]) && $_GET[$item] != "") {
                $cond[$item] = htmlspecialchars(urlencode($_GET[$item]));
            }
        }
        $qrst = "true ";
        if (isset($cond['wanna_down']))
            $qrst .= "and birthday <= NOW() - Interval {$cond['wanna_down']} year ";
        if (isset($cond['wanna_up']))
            $qrst .= "and birthday >= NOW() - Interval {$cond['wanna_up']} year ";
        if (isset($cond['cb_male']) and isset($cond['cb_fmale']))
            ;
        else if (isset($cond['cb_fmale']))
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
        $qrst .= "LIMIT " . ((int)$_GET['page'] - 1) * 20 . ", 20";
        //                echo $qrst;
        $qr = Database::query('user', $qrst);
        //                if(is_string($qr))
        //                    echo $qr;
        if (is_string($qr))
            echo "<div class='errorSmall'>$qr</div>";
        else {
            echo "<div class='demo-detail-title'>本页有结果 " . mysqli_num_rows($qr) . " 个</div>";
            while ($obj = mysqli_fetch_object($qr)) {
                $ur = new User($obj);
                if (!$ur->valid() or $ur->banned)
                    continue;

                ?>
                <div class="m-grids-2">
                    <div class="grids-item" style="height: 7rem;width: 30%;">
                        <h2 class="m-celltitle" style="font-size: .6rem; color: black">
                                <span class="blueColor noUnderline">
                                    <a href="profile.php?uid=<?php echo $ur->id ?>"><?php echo $ur->nickname ?></a>
                                </span>
                        </h2>
                        <a href="profile.php?uid=<?php echo $ur->id ?>"><img
                                    class="imgMember96x126" src="/<?php echo $ur->img_path ?>" width="96"
                                    height="126" alt="<?php echo $ur->nickname ?>"
                                    title="<?php echo $ur->nickname ?>" border="0"></a>

                        <strong><?php echo $ur->age() ?> 岁</strong>
                        <img src="//ppwimg.com/img/icon_<?php echo $ur->gender ?>.gif" width="14" height="17"
                             align="bottom">
                        <span class="tinyFont">希望与年龄在 <?php echo $ur->wanna_down ?> 岁和 <?php echo $ur->wanna_up ?>
                            岁之间的人交朋友</span>
                        <div class="grayBar">
                            <div class="grayBarLastLogin tinyFont darkGrayColor">登录于 <?php echo $ur->last_login ?></div>
                            <div class="grayBarMemberSince tinyFont darkGrayColor">
                                注册于 <?php echo $ur->signup_date ?></div>
                        </div>
                    </div>
                    <div class="grids-item" style="height: 7rem;width: 70%;">

                        <div class="demo-detail-text"><?php echo cut_str($ur->self_introduction, 0, 120) ?></div>
                        <div style="position: absolute; bottom: .5rem; right: .5rem">
                            <a href="profile.php?uid=<?php echo $ur->id ?>">
                                <strong>查看资料</strong>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="demo-small-pitch"></div>
                <?php
            }
            $disabled_lastpage = $_GET['page'] == 1 ? "disabled" : "";
            $cururl = GetCurUrl();
            $last_page = url_set_value($cururl, 'page',  ((int)$_GET['page'] - 1));
            $next_page = url_set_value($cururl, 'page',  ((int)$_GET['page'] + 1));
            $disabled_nextpage = "";
            echo <<< TAG
            <div class="m-grids-2" style="background-color: #f0f0f0f0">
            <div class="grids-item">
                <a href="$last_page">
                        <button type="button" class="btn-block btn-primary" $disabled_lastpage>上一页</button>
                </a>
            </div>
            <div class="grids-item">
                <a href="$next_page">
    
                        <button type="button" class="btn-block btn-primary" $disabled_nextpage>下一页</button>
            
                </a>
            </div>
                     
            </div>
TAG;
        }
        ?>
    </header>
</section>

<?php
require_once "wapfooter.php";
?>
