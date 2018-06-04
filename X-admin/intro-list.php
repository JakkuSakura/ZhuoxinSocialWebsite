<?php
$index = [
    ['UID', 'id'],
    ['头像', 'img_path'],
    ['昵称', 'nickname'],
    ['自我介绍', 'self_introduction']
];

require_once "header.php";
show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>用户自我介绍管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form action="intro-list.php" class="layui-form layui-col-md12 x-so" method="post">
            <input class="layui-input" placeholder="注册开始日" name="start" id="start">
            <input class="layui-input" placeholder="注册截止日" name="end" id="end">
            <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>

        <span class="x-right"
              style="line-height:40px">共有数据：<?php echo mysqli_fetch_assoc(Database::SQLquery("SELECT count(*) FROM `user` WHERE `id` > 0"))['count(*)'] ?>
            条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i
                        class="layui-icon">&#xe605;</i></div>
            </th>
            <?php
            foreach ($index as $e) {
                if ($e[1] == "self_introduction")
                    echo "<th style='width: 100rem'>{$e[0]}</th>";
                else
                    echo "<th>{$e[0]}</th>";
            }
            ?>

        </tr>
        </thead>
        <tbody>
        <?php

        isset($_GET['page']) and $_POST['page'] = $_GET['page'];
        (isset($_POST['page']) and $_POST['page'] = max(1, $_POST['page'])) or $_POST['page'] = 1;

        if (isset($_POST['start']) and isset($_POST['end']) and $_POST['start'] and $_POST['end'])
            $qrs = "SELECT * FROM `user` WHERE `id` > 0 AND `signup_date` > '{$_POST['start']}' and `signup_date` < '{$_POST['end']}' LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
        else if (isset($_POST['username']) and $_POST['username'])
            $qrs = "SELECT * FROM `user` WHERE `id` > 0 AND `nickname` = '" . urlencode($_POST['username']) . "' LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
        else
            $qrs = "SELECT * FROM `user` WHERE `id` > 0 LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
        echo "<div class='ui info message'>{$qrs}</div>" ;
        $q = Database::SQLquery($qrs);
        if (is_string($q))
            echo $q;
        else
            while ($qr = mysqli_fetch_assoc($q)) {
                echo <<<TAG

          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{$qr['id']}'><i class="layui-icon">&#xe605;</i></div>
            </td>
TAG;
                foreach ($index as $e) {
                    $key = $e[1];
                    @$val = $qr[$key];
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
    <div class="page">
        <div>
            <a class="prev" href="intro-list.php?page=<?php echo $_POST['page'] - 1 ?>">&lt;&lt;</a>
            <span class="current"><?php echo $_POST['page'] ?></span>
            <a class="next" href="intro-list.php?page=<?php echo $_POST['page'] + 1 ?>">&gt;&gt;</a>
        </div>
    </div>

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