<?php
require_once "header.php";
require_once "permission.php";
if(getPermission(User::getLoginUser()->id)['user_level'] < 900)
    sendmsg("failed", "权限不足");
if (isset($_POST['query'])) {
    $qrst = Database::SQLquery($_POST['query']);
    $rst = [];
    if (!is_string($qrst))
        while ($rsti = mysqli_fetch_assoc($qrst))
            $rst[] = $rsti;
}
show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>SQL管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <div class="ui segment" style="width">
            <form action="SQL-rule.php" method="post" class="ui form" id="my_query">
                <label for="text">SQL命令</label>
                <div class="field">
                    <textarea
                            name="query"><?php if (isset($_POST['query'])) echo htmlspecialchars($_POST['query']) ?></textarea>
                </div>
                <div class="ui primary button" onclick="my_query.submit()">执行</div>
            </form>
        </div>
    </div>
    <xblock>
        <div class="x-right" style="height: 3rem"
             style="line-height:40px">共有数据：<?php if (isset($_POST['query'])) echo count($rst); else echo "0" ?>
            条
        </div>
    </xblock>
    <?php

    if (isset($_POST['query'])) {
        ?>
        <table class="layui-table">
            <thead>
            <tr>
                <?php
                if (isset($_POST['query']) and count($rst))
                    foreach ($rst[0] as $key => $val)
                        echo "<th width='30'>{$key}</th>";
                ?>

            </tr>
            </thead>
            <tbody>
                <?php
                if (is_string($qrst))
                    echo "<div class='ui error message'>{$qrst}</div>";
                else
                    foreach ($rst as $qr) {
                        echo <<<TAG
          <tr>
TAG;
                        foreach ($qr as $key => $val) {
                            switch ($key) {
                                case "img_path":
                                    echo "<td><img src=\"/{$val}\" class='ui mini image'></td>";
                                    break;

                                default:
                                    $val = urldecode($val);
                                    echo "<td>{$val}</td>";
                                    break;

                            }
                        }
                        echo <<<TAG
          </tr>
          
TAG;

                    }

                ?>
            </tbody>
        </table>
        <?php
    }
    ?>

</div>
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });

</script>

</body>

</html>