<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/19
 * Time: 21:28
 */
require_once "wapheader.php";
?>

<section class="g-flexview">

    <header class="m-navbar" id="navMain">
        <a href="index.php" class="navbar-item"><i class="back-ico"></i></a>
        <div class="navbar-center"><span class="navbar-title">搜索用户</span></div>
    </header>

    <section class="g-scrollview">

        <form action="/wap/searchResult.php" method="get">
            <div class="m-celltitle" style="margin-top: 1.5em">基本信息</div>
            <div class="m-cell demo-small-pitch">
                <div class="cell-item">
                    <div class="cell-left">昵称：</div>
                    <div class="cell-right"><input type="text" class="cell-input" placeholder="请输入您要检索的昵称"
                                                   autocomplete="off" name="strName"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left">年龄：</div>
                    <div class="cell-right">
                        <input type="text" class="cell-input" style="margin-top:5px; width: 2em;text-align: center;/*border: solid; border-width: .5px;*/" name="wanna_down" value="10"
                               autocomplete="off" >岁到
                        <input type="text" class="cell-input" style="margin-top:5px; width: 2em;text-align: center;/*border: solid; border-width: .5px;*/" name="wanna_up" value="40"
                               autocomplete="off">岁
                    </div>
                </div>
            </div>


            <div class="m-celltitle">搜索的性别</div>
            <div class="m-cell demo-small-pitch">
                <label class="cell-item">
                    <span class="cell-left">男</span>
                    <label class="cell-right">
                        <input type="checkbox" name="cb_male" checked>
                        <i class="cell-checkbox-icon"></i>
                    </label>
                </label>
                <label class="cell-item">
                    <span class="cell-left">女</span>
                    <label class="cell-right">
                        <input type="checkbox" name="cb_fmale" checked>
                        <i class="cell-checkbox-icon"></i>
                    </label>
                </label>
            </div>

            <div class="m-celltitle">其他信息</div>
            <div class="m-cell demo-small-pitch">
                <div class="cell-item">
                    <div class="cell-left">省份：</div>
                    <div class="cell-right"><input type="text" class="cell-input" placeholder="请输入您要检索的省份"
                                                   autocomplete="off" name="strProvince"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left">城市：</div>
                    <div class="cell-right"><input type="text" class="cell-input" placeholder="请输入您要检索的城市" autocomplete="off" name="strCity"></div>
                </div>
                <div class="cell-item">
                    <div class="cell-left">兴趣爱好：</div>
                    <div class="cell-right" ><input type="text" class="cell-input" placeholder="请输入您要检索的兴趣爱好" autocomplete="off" name="strHobby"></div>
                </div>
            </div>
            <input type="hidden" name="page" value="1">
            <input type="submit" class="btn-block btn-primary" value="搜索">

        </form>

    </section>
</section>
<?php
require_once "wapfooter.php";
?>
