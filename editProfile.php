<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/29
 * Time: 9:46
 */
require_once "header.php";
require_once ROOT . "jcrop/config.php";
require_once ROOT . "MarkdownParser.php";

$user = User::getLoginUser();
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <title>编辑资料 卓信</title>
    <link rel="stylesheet" href="/scripts/css/semantic.min.css">
    <script src="/scripts/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/jcrop/jcrop/jquery.Jcrop.min.js"></script>
    <script src="/scripts/semantic.min.js"></script>
    <script src="/scripts/myTools.js"></script>
    <script src="/scripts/adblock.js"></script>
    <link type="text/css" rel="stylesheet" href="/jcrop/file-uploader/fileuploader.css"/>
    <script type="text/javascript" language="javascript" src="/jcrop/file-uploader/fileuploader.min.js"></script>


    <link type="text/css" rel="stylesheet" href="/jcrop/jcrop/jquery.Jcrop.min.css"/>
    <script type="text/javascript" language="javascript" src="/jcrop/jcrop/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" language="javascript">
        <?php

        if (!User::isLoggedIn())
            echo "window.location.href='/login.php';"
        ?>
        $(document)
            .ready(function () {
            // create sidebar and attach to menu open
            $('.ui.sidebar')
                .sidebar('attach events', '.toc.item')
            ;

        });
        var g_oJCrop = null;

        $(function () {
            new qq.FileUploader({
                element: document.getElementById('upload_avatar'),
                action: "/jcrop/controller.php?task=ajax_upload_avatar",
                multiple: false,
                disableDefaultDropzone: true,
                allowedExtensions: ["<?php echo implode('", "', explode(', ', ALLOW_UPLOAD_IMAGE_TYPES)); ?>"],
                uploadButtonText: '选择头像图片',
                onComplete: function (id, fileName, json) {
                    if (json.success) {
                        if (g_oJCrop !== null) g_oJCrop.destroy();

                        $("#crop_tmp_avatar").val(json.tmp_avatar);
                        $("#crop_container").show();
                        $("#crop_target, #crop_preview").html('<img src="/jcrop/tmp/' + json.tmp_avatar + '">');

                        $('#crop_target').find("img").Jcrop({
                            allowSelect: false,
                            onChange: updatePreview,
                            onSelect: updatePreview,
                            aspectRatio: <?php echo AVATAR_WIDTH / AVATAR_HEIGHT; ?>,
                            minSize: [<?php echo AVATAR_WIDTH; ?>, <?php echo AVATAR_HEIGHT; ?>]
                        }, function () {
                            g_oJCrop = this;

                            var bounds = g_oJCrop.getBounds();
                            var x1, y1, x2, y2;
                            if (bounds[0] / bounds[1] > <?php echo AVATAR_WIDTH; ?>/<?php echo AVATAR_HEIGHT; ?>) {
                                y1 = 0;
                                y2 = bounds[1];

                                x1 = (bounds[0] - <?php echo AVATAR_WIDTH; ?> * bounds[1] /<?php echo AVATAR_HEIGHT; ?>) / 2
                                x2 = bounds[0] - x1;
                            }
                            else {
                                x1 = 0;
                                x2 = bounds[0];

                                y1 = (bounds[1] - <?php echo AVATAR_HEIGHT; ?> * bounds[0] /<?php echo AVATAR_WIDTH; ?>) / 2
                                y2 = bounds[1] - y1;
                            }
                            g_oJCrop.setSelect([x1, y1, x2, y2]);
                        });
                    }
                    else {
                        myalert(json.description);
                    }
                }
            });


        });


        function updatePreview(c) {
            $('#crop_x1').val(c.x);
            $('#crop_y1').val(c.y);
            $('#crop_x2').val(c.x2);
            $('#crop_y2').val(c.y2);
            $('#crop_w').val(c.w);
            $('#crop_h').val(c.h);

            if (parseInt(c.w) > 0) {
                var bounds = g_oJCrop.getBounds();

                var rx = <?php echo AVATAR_WIDTH; ?> / c.w;
                var ry = <?php echo AVATAR_HEIGHT; ?> / c.h;

                $('#crop_preview').find("img").css({
                    width: Math.round(rx * bounds[0]) + 'px',
                    height: Math.round(ry * bounds[1]) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        }


        function saveCropAvatar() {
            if ($("#crop_tmp_avatar").val() === "") {
                myalert("您还没有上传头像");
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/jcrop/controller.php?task=ajax_crop",
                data: $("#form_crop_avatar").serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.success) {
                        $("#crop_tmp_avatar").val("");
                        $("#crop_container").hide();

                        $("#my_avatar").html('<img src="/' + json.avatar + '">');
                    }
                    else {
                        myalert(json.description);
                    }
                }
            });
        }
    </script>

    <style>
        .ui.ribbon.label {
            margin-left: 1rem;
        }
    </style>

<head>
<body>

<div class="ui small modal">
    <i class="close icon"></i>
    <div class="header" id="header">
        提示
    </div>
    <div class="content">
        <div class="description">
            <p id="message"></p>
        </div>
    </div>
    <div class="actions">
        <div class="ui black deny button">
            OK
        </div>
    </div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
    <?php showmenu(true); ?>
</div>

<div class="ui fixed menu">
    <div class="ui container">
        <?php showmenu( false ); ?>
    </div>
</div>

<div class="pusher">
    <div class="ui raised very padded text container segment">

        <img src="/img/biglogo.png" alt="卓信" class="ui centered medium image">
        <h1 class="ui dividing header">
            编辑资料
        </h1>

        <h3 class="ui dividing header">详细资料</h3>
        <form action="/action/editProfileSettingsAction.php" method="post" name="myForm" class="ui form">

            <label class="ui ribbon label" for="strRealName">真实姓名:</label>
            <div class="field">
                <input class="text" type="text" maxlength="40" name="strRealName"
                       id="strRealName" value="<?php echo $user->realname ?>">

            </div>


            <label class="ui ribbon label" for="strNickName">昵称:</label>
            <div class="field">
                <input class="text" type="text" maxlength="40" name="strNickName"
                       id="strNickName" value="<?php echo $user->nickname ?>">

            </div>


            <label class="ui ribbon label">性别:</label>

            <div class="field">
                <select class="text" id="strGender" name="strGender">
                    <option value=""></option>
                    <option value="m" <?php if ($user->gender == 'm') echo 'selected="selected"'; ?> >
                        男
                    </option>
                    <option value="f" <?php if ($user->gender == 'f') echo 'selected="selected"'; ?> >
                        女
                    </option>
                    <option value="s" <?php if ($user->gender == 's') echo 'selected="selected"'; ?> >
                        保密
                    </option>
                </select>

            </div>


            <label class="ui ribbon label">感情状态:</label>
            <div class="field">
                <select class="text" id="strStatus" name="intRelationshipStatus">
                    <option value="0"></option>
                    <option value="1" <?php if ($user->relationship == 1) echo 'selected="selected"'; ?> >
                        单身
                    </option>
                    <option value="2" <?php if ($user->relationship == 2) echo 'selected="selected"'; ?> >
                        恋爱
                    </option>
                    <option value="3" <?php if ($user->relationship == 3) echo 'selected="selected"'; ?> >
                        订婚
                    </option>
                    <option value="4" <?php if ($user->relationship == 4) echo 'selected="selected"'; ?> >
                        已婚
                    </option>
                </select>
            </div>


            <label class="ui ribbon label">生日:</label>
            <div class="field">
                <input type="date" value="<?php echo($user->birthday); ?>" disabled>
            </div>


            <label class="ui ribbon label" for="strState">省份:</label>
            <div class="field">
                <input type="text" class="text" maxlength="40" name="strState" id="strState"
                       value="<?php echo $user->province ?>">

            </div>


            <label class="ui ribbon label" for="strCity">城市:</label>
            <div class="field">
                <input type="text" class="text" maxlength="40" name="strCity" id="strCity"
                       value="<?php echo $user->city ?>">

            </div>


            <label class="ui ribbon label" for="strHabit">兴趣爱好（用空格分割）:</label>
            <div class="field">
                <input type="text" class="text" maxlength="40" name="strHabit" id="strHabit"
                       value="<?php echo $user->habits ?>">

            </div>


            <label class="ui ribbon label" for="strHabit">原始密码:</label>
            <div class="field">
                <input type="password" class="" maxlength="40" name="strOldPassword" id="strOldPassword"
                       value="">
            </div>


            <label class="ui ribbon label" for="strHabit">新密码:</label>
            <div class="field">
                <input type="password" class="text" maxlength="40" name="strNewPassword" id="strNewPassword"
                       value="">
            </div>


            <br>
            <script language="javascript" type="text/javascript">
                function sendsettings() {
                    ajaxSubmit(myForm, function (data) {
                        obj = {msg: data};
                        $("#message").html(obj.msg);
                        $('.small.modal')
                            .modal('show')
                        ;
                        $("#sendbt").val("保存").attr("disabled", false);
                    });
                }
            </script>

            <input type="button" value="保存" class="ui primary button" title="保存" id="sendbt" name="sendbt"
                   onclick="this.disabled = true;sendsettings()">
        </form>


        <h3 class="ui dividing header">编辑头像</h3>
        <div class="ui text container">
            <label class="ui ribbon label">当前头像:</label>
            <div id="my_avatar" class="field">
                <img src="/<?php echo $user->img_path ?>">
            </div>
            <div id="crop_container" style="display:none;" class="field">
                <label class="ui ribbon label">裁切头像：</label>


                <div id="crop_target"></div>
                <label for="" class="ui ribbon label">预览头像</label>
                <div id="crop_preview"
                     style="width:<?php echo AVATAR_WIDTH; ?>px; height:<?php echo AVATAR_HEIGHT; ?>px; overflow:hidden;">
                </div>

            </div>
            <label class="ui ribbon label">上传新头像</label>
            <div class="field">
                <div id="upload_avatar" class="ui primary button"></div>
            </div>
            <br>
            <input type="button" id="btn_save_crop" value="保存" class="ui primary button"
                   onClick="saveCropAvatar();"/>

            <form id="form_crop_avatar">
                <input type="hidden" name="tmp_avatar" id="crop_tmp_avatar" value="">
                <input type="hidden" name="x1" id="crop_x1" value="">
                <input type="hidden" name="y1" id="crop_y1" value="">
                <input type="hidden" name="x2" id="crop_x2" value="">
                <input type="hidden" name="y2" id="crop_y2" value="">
                <input type="hidden" name="w" id="crop_w" value="">
                <input type="hidden" name="h" id="crop_h" value="">
            </form>
        </div>
        <h3 class="ui dividing header">编辑个人介绍</h3>
        <form action="/action/editProfileAction.php" method="post" class="ui form" id="protext">
            <textarea name="strProfileText"><?php echo $user->self_introduction; ?></textarea>
            <div class="ui pointing label">支持基本的Markdown标签，可以试一试<b style="color: black">**加粗**</b>,<em>*倾斜*</em></div>
            <input type="button" value="修改" class="ui right floated primary button" onclick="ajaxSubmit(protext, function(data) {
              myalert(data);
            })">
        </form>
    </div>
<?php
require_once "footer.php";
