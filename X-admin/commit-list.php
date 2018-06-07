<?php
require_once "header.php";
require_once "permission.php";
if (isset($_POST['oper'])) {
    switch ($_POST['oper']) {
        case 'delete':
            check("delete_commit");
            Database::SQLquery("DELETE FROM `message` WHERE id=" . (int)$_POST['mid']);
            sendmsg("ignore", "删除{$_POST['cid']}成功");
            break;
        default:
            sendmsg("failed", "unknown");
    }
}
$index = [
    ['MID', 'id'],
    ['接收者', 'user1id'],
    ['发送者', 'user2id'],
    ['文本', 'text'],
    ['时间', 'date'],
    ['删除', 'delete']
];


show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>评论管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form action="commit-list.php" class="layui-form layui-col-md12 x-so" method="post">
            <input class="layui-input" placeholder="发送开始日" name="start" id="start">
            <input class="layui-input" placeholder="发送截止日" name="end" id="end">
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
                switch ($e[1])
                {
                    case "text":
                        echo "<th width='240'>{$e[0]}</th>";
                        break;
                    default:
                        echo "<th>{$e[0]}</th>";
                        break;
                }

            }
            ?>

        </tr>
        </thead>
        <tbody>
        <?php

        isset($_GET['page']) and $_POST['page'] = $_GET['page'];
        (isset($_POST['page']) and $_POST['page'] = max(1, $_POST['page'])) or $_POST['page'] = 1;

        if (isset($_POST['start']) and isset($_POST['end']) and $_POST['start'] and $_POST['end'])
            $qrs = "SELECT * FROM `commit` WHERE `id` > 0 AND `date` > '{$_POST['start']}' and `date` < '{$_POST['end']}' LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
        else
            $qrs = "SELECT * FROM `commit` WHERE `id` > 0 LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
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

                        case "delete":
                            echo "<td><div class=\"ui negative button\" onclick='commit_del(this,{$qr['id']})'><i class='remove user icon'></i>删除</div></td>";
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
            <a class="prev" href="commit-list.php?page=<?php echo $_POST['page'] - 1 ?>">&lt;&lt;</a>
            <span class="current"><?php echo $_POST['page'] ?></span>
            <a class="next" href="commit-list.php?page=<?php echo $_POST['page'] + 1 ?>">&gt;&gt;</a>
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


    /*用户-删除*/
    function commit_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("commit-list.php", {
                oper: "delete",
                cid: id
            }, function (data) {
                msg = JSON.parse(data);
                myalert(msg);
                if (msg.state !== "failed") {
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!', {icon: 1, time: 1000});
                }
            });
        });

    }


    function delAll(obj, argument) {

        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？' + data, function (index) {
            //捉到所有被选中的，发异步进行删除
            data.forEach(function (val, index) {
                $.post("commit-list.php", {
                    oper: "delete",
                    cid: val
                }, function (data) {
                    msg = JSON.parse(data);
                    myalert(msg);
                    if (msg.state !== "failed")
                        $("div[data-id=" + val + "]").parents("tr").remove();
                });

            });
            layer.msg('操作完成', {icon: 1, time: 1000});
//            $(".layui-form-checked").not('.header').parents('tr').remove();
        });

    }
</script>
</body>

</html>