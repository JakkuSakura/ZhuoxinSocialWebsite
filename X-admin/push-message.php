<?php
require_once "header.php";
require_once "permission.php";
if (isset($_POST['oper'])) {
    $ur = User::getUser("id", (int)$_POST['user']);
    if (!$ur->valid())
        sendmsg("failed", "用户{$_POST['user']}不存在");
    switch ($_POST['oper']) {
        case 'email':
            check("send_email");
            require_once ROOT . "sendEmail.php";
            sendEmail($ur->email, $_POST['subject'], $_POST['text']);
            sendmsg("ignore", "已经成功发送到{$ur->id}");
            break;
        case 'message':
            check("send_message");
            $data = $_POST['subject'] . "\n" . $_POST['text'];
            $data = urlencode($data);
            Database::SQLquery("INSERT INTO `message` (user1id, user2id, text, date) VALUES ({$ur->id}, {$user->id}, '{$data}', NOW())");
            sendmsg("ignore", "已经成功发送到{$ur->id}");
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
];
check("see_user");
show();
?>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a><cite>消息</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <div class="layui-btn layui-btn-primary" onclick="sendEmail()"><i class="mail outline icon"></i>发送邮件</div>
        <div class="layui-btn layui-btn-primary" onclick="sendMessage()"><i class="announcement icon"></i>发送站内信</div>

        <span class="x-right"
              style="line-height:40px">共有数据：<?php echo mysqli_fetch_assoc(Database::SQLquery("SELECT count(*) FROM `user` WHERE `id` > 0"))['count(*)'] ?>
            条</span>
    </xblock>
    <div class="layui-row">
        <div class="ui segment" style="width">
            <form action="" method="post" class="ui form" id="protext">
                <label for="subject">标题</label>
                <div class="field">
                    <input type="text" id="subject">

                </div>
                <label for="text">内容</label>
                <div class="field">
                    <textarea id="text"></textarea>

                </div>
            </form>
        </div>
    </div>
    <div class="layui-row">
        <form action="push-message.php" class="layui-form layui-col-md12 x-so" method="post">
            <input class="layui-input" placeholder="注册开始日" name="start" id="start">
            <input class="layui-input" placeholder="注册截止日" name="end" id="end">
            <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary">
                    <i class="layui-icon">&#xe605;</i></div>
            </th>
            <?php
            foreach ($index as $e)
                echo "<th>{$e[0]}</th>";
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
            echo $q . $qrs;
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
            <a class="prev" href="push-message.php?page=<?php echo $_POST['page'] - 1 ?>">&lt;&lt;</a>
            <span class="current"><?php echo $_POST['page'] ?></span>
            <a class="next" href="push-message.php?page=<?php echo $_POST['page'] + 1 ?>">&gt;&gt;</a>
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


    function send(type) {
        var data = tableCheck.getData();
        data.forEach(function (val, index) {
            console.log("Sending");
            //捉到所有被选中的，发异步进行删除
            $.post("push-message.php", {
                oper: type,
                user: val,
                text: $("#text").val(),
                subject: $("#subject").val()
            }, function (data) {
                msg = JSON.parse(data);
                myalert(msg);
            });

        });
        layer.msg('操作完成', {icon: 1, time: 1000});
    }

    function sendEmail() {
        send("email");

    }

    function sendMessage() {
        send("message");
    }
</script>

</body>

</html>