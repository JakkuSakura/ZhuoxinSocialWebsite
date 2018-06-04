<?php
require_once "header.php";
show();
?>
<body>
<div class="x-body">
    <blockquote class="layui-elem-quote">欢迎使用卓信社交平台后台</blockquote>
    <fieldset class="layui-elem-field">
        <legend>信息统计</legend>
        <div class="layui-field-box">

            <table class="layui-table">
                <thead>
                <tr>
                    <th colspan="2" scope="col">服务器信息</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>服务器IP地址</td>
                    <td><?php echo $_SERVER['SERVER_ADDR'] ?></td>
                </tr>
                <tr>
                    <td>服务器域名</td>
                    <td><?php echo $_SERVER['HTTP_HOST'] ?></td>
                </tr>
                <tr>
                    <td>服务器端口</td>
                    <td><?php echo $_SERVER['SERVER_PORT'] ?></td>
                </tr>
                <tr>
                    <td>服务器软件和版本</td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
                </tr>
                <tr>
                    <td>本文件所在文件夹</td>
                    <td><?php echo dirname(__FILE__) ?></td>
                </tr>
                <tr>
                    <td>服务器操作系统</td>
                    <td><?PHP echo PHP_OS; ?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </fieldset>

</div>
</body>
</html>