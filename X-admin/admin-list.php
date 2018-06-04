<?php
require_once "header.php";
require_once "permission.php";
if (isset($_POST['oper'])) {
    $ur = User::getUser("id", (int)$_POST['user']);
    if (!$ur->valid())
        sendmsg("failed", "用户{$_POST['user']}不存在");
    switch ($_POST['oper']) {
        case 'cancel':
            check("cancel_admin");
            Database::update('user', "is_admin", 0, "id=" . $ur->id);
            Database::SQLquery("DELETE FROM `permission` WHERE `id`=" . $ur->id);
            sendmsg("successful", "取消管理员{$ur->id}:{$ur->nickname}成功");
            break;
        case 'set_admin':
            if (getPermission($user->id)['user_level'] < 800)
                sendmsg("failed", "没有权限");
            Database::update('user', "is_admin", 1, "id=" . $ur->id);
            Database::SQLquery("INSERT INTO `permission`(`id`, `user_level`, `see_user`, `ban_user`, `see_admin`, `cancel_admin`, `send_message`, `delete_message`, `send_email`, `delete_user`, `see_message`, `see_commits`,
 `see_topics`, `delete_commit`, `see_intro`) VALUES  ({$ur->id},100,1,1,0,0,0,0,0,0,0,0,0,0,0)");
            sendmsg("successful", "设置管理员{$ur->id}:{$ur->nickname}成功");
            break;
        default:
            sendmsg("failed", "unknown");
    }
    sendmsg("failed", "WTF");
}
$index = [
    ['UID', 'id'],
    ['头像', 'img_path'],
    ['昵称', 'nickname'],
    ['性别', 'gender'],
    ['邮箱', 'email'],
    ['注册时间', 'signup_date'],
    ['管理员', 'is_admin'],
    ['取消', 'cancel']
];
check("see_admin");
show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>管理员管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form action="admin-list.php" class="layui-form layui-col-md12 x-so" method="post">
            <input class="layui-input" placeholder="注册开始日" name="start" id="start">
            <input class="layui-input" placeholder="注册截止日" name="end" id="end">
            <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <xblock>
        <form action="admin-list.php" method="post" class="ui form">
            <div class="inline field">
                <input type="text" id="id">
                <div class="ui primary button" onclick="set_admin(id)"><i class="add icon"></i>添加管理员</div>
                <span class="x-right"
                      style="line-height:40px">共有数据：<?php echo mysqli_fetch_assoc(Database::SQLquery("SELECT count(*) FROM `user` WHERE `id` > 0"))['count(*)'] ?>
                    条</span>
            </div>
        </form>

    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
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

        $qrs = "SELECT * FROM `user` WHERE `id` > 0 AND `is_admin` = 1 LIMIT " . ((int)$_POST['page'] - 1) * 30 . ", 30";
        echo "<div class='ui info message'>{$qrs}</div>";
        $q = Database::SQLquery($qrs);
        if (is_string($q))
            echo $q;
        else
            while ($qr = mysqli_fetch_assoc($q)) {
                echo <<<TAG

    <tr>
TAG;
                foreach ($index as $e) {
                    $key = $e[1];
                    @$val = $qr[$key];
                    switch ($key) {
                        case "img_path":
                            echo "<td><img src=\"/{$val}\" class='ui mini image'></td>";
                            break;
                        case "cancel":
                            echo "<td><div class=\"ui negative button\" onclick='admin_cancel(this,{$qr['id']})'><i class='cancel icon'></i>取消</div></td>";
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
            <a class="prev" href="admin-list.php?page=<?php echo $_POST['page'] - 1 ?>">&lt;&lt;</a>
            <span class="current"><?php echo $_POST['page'] ?></span>
            <a class="next" href="admin-list.php?page=<?php echo $_POST['page'] + 1 ?>">&gt;&gt;</a>
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


    function admin_cancel(obj, id) {
        layer.confirm('确认要取消吗？', function (index) {
            //发异步取消数据
            $.post("admin-list.php", {
                oper: "cancel",
                user: id
            }, function (data) {
                msg = JSON.parse(data);
                myalert(msg);
                if (msg.state !== "failed") {
                    $(obj).parents("tr").remove();
                }
            });
        });
    }

    function set_admin(id) {
        layer.confirm('确认要设定管理员' + id + '吗？', function (index) {
            //发异步取消数据
            $.post("admin-list.php", {
                oper: "set_admin",
                user: $("#id").val()
            }, function (data) {
                msg = JSON.parse(data);
                myalert(msg);

                if (msg.state !== "failed") {
                    setTimeout('window.location.href = "admin-list.php";', 2000);

                }
            });
        });


    }
</script>
</body>

</html>