<?php
require_once "header.php";
require_once "permission.php";
check("user_level");
show();
?>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href=".">卓信社交平台后台</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
<!--    <ul class="layui-nav left fast-add">-->
<!--        <li class="layui-nav-item">-->
<!--            <a href="javascript:;">+新增</a>-->
<!--            <dl class="layui-nav-child"> <!-- 二级菜单 -->-->
<!---->
<!--        </li>-->
<!--    </ul>-->
    <ul class="layui-nav right">
        <li class="layui-nav-item">
            <a href="javascript:;"><?php echo $user->nickname?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
<!--                <dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>-->
<!--                <dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>-->
                <dd><a href="/logout.php">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li class="open">
                <a href="javascript:;">
                    <i class="user icon"></i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right"></i>
                </a>
                <ul class="sub-menu" style="display: block;">
                    <li>
                        <a _href="member-list.php">
                            <i class="iconfont"></i>
                            <cite>会员列表</cite>

                        </a>
                    </li>
                    <li>
                        <a _href="intro-list.php">
                            <i class="iconfont"></i>
                            <cite>管理自我介绍</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="usercreater.php">
                            <i class="iconfont"></i>
                            <cite>生成用户</cite>

                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="javascript:;">
                    <i class="mail icon"></i>
                    <cite>消息</cite>
                    <i class="iconfont nav_right"></i>
                </a>
                <ul class="sub-menu" style="display: none;">
                    <li>
                        <a _href="push-message.php">
                            <i class="iconfont"></i>
                            <cite>发布消息</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="message-list.php">
                            <i class="iconfont"></i>
                            <cite>管理消息</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="commit-list.php">
                            <i class="iconfont"></i>
                            <cite>管理评论</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="javascript:;">
                    <i class="terminal icon"></i>
                    <cite>高级管理</cite>
                    <i class="iconfont nav_right"></i>
                </a>
                <ul class="sub-menu" style="display: none;">
                    <li>
                        <a _href="admin-list.php">
                            <i class="iconfont"></i>
                            <cite>管理员列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="SQL-rule.php">
                            <i class="iconfont"></i>
                            <cite>SQL管理</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="javascript:;">
                    <i class="area chart icon"></i>
                    <cite>系统统计</cite>
                    <i class="iconfont nav_right"></i>
                </a>
                <ul class="sub-menu" style="display: none;">
                    <li>
                        <a _href="https://tongji.baidu.com">
                            <i class="iconfont"></i>
                            <cite>百度统计</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="https://www.17ce.com/">
                            <i class="iconfont"></i>
                            <cite>网站测速</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li>我的桌面</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='welcome.php' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2018 卓新社交</div>
</div>
<!-- 底部结束 -->

</body>
</html>