<?php
require_once "header.php";
require_once "permission.php";
if (isset($_POST['oper'])) {
    $ur = User::getUser("id", (int)$_POST['user']);
    if (!$ur->valid())
        sendmsg("failed", "用户{$_POST['user']}不存在");
    switch ($_POST['oper']) {
        case 'ban':
            check("ban_user");
            Database::update('user', "banned", !$ur->banned, "id=" . $ur->id);
            sendmsg("ignore", ($ur->banned ? "解封" : "封禁") . "{$ur->id}:{$ur->nickname}成功");
            break;
        case 'delete':
            check("delete_user");
            $p = getPermission($ur->id);
            $myp = getPermission(User::getLoginUser()->id);
            if ($p["user_level"] < $p["user_level"])
            {
                sendmsg("failed", "你没有权限");
            }
            Database::SQLquery("DELETE FROM `user` WHERE id=" . $ur->id);
            sendmsg("ignore", "删除{$ur->id}:{$ur->nickname}成功");
            break;
        default:
            sendmsg("failed", "unknown");
    }
}
$index = [
    ['UID', 'id'],
    ['头像', 'img_path'],
    ['昵称', 'nickname'],
    ['性别', 'gender'],
    ['邮箱', 'email'],
    ['注册时间', 'signup_date'],
    ['封禁', 'banned'],
    ['删除', 'delete']
];

check("see_user");
show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>用户管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form action="member-list.php" class="layui-form layui-col-md12 x-so" method="post">
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
                if ($e[0])
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
                        case "banned":
                            $selected = $val ? "selected" : "";
                            echo "<td><div class=\"ui fitted toggle checkbox\"><input type=\"checkbox\" {$selected} onclick='member_ban(this,{$qr['id']})'><label></label></div></td>";
                            break;
                        case "delete":
                            echo "<td><div class=\"ui negative button\" onclick='member_del(this,{$qr['id']})'><i class='remove user icon'></i>删除</div></td>";
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
            <a class="prev" href="member-list.php?page=<?php echo $_POST['page'] - 1 ?>">&lt;&lt;</a>
            <span class="current"><?php echo $_POST['page'] ?></span>
            <a class="next" href="member-list.php?page=<?php echo $_POST['page'] + 1 ?>">&gt;&gt;</a>
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


    /*用户-封禁*/
    function member_ban(obj, id) {
        $.post("member-list.php", {
            oper: "ban",
            user: id
        }, function (data) {
            msg = JSON.parse(data);
            myalert(msg);
        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("member-list.php", {
                oper: "delete",
                user: id
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
                $.post("member-list.php", {
                    oper: "delete",
                    user: val
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