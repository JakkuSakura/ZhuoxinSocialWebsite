<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/28
 * Time: 9:45
 */
require_once "header.php";
?>
    <!DOCTYPE html>
    <html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properties -->
        <title>下载客户端——卓信社交</title>
        <link rel="stylesheet" type="text/css" href="/scripts/css/semantic.min.css">

        <script src="/scripts/jquery-3.3.1.min.js"></script>
        <script src="/scripts/semantic.min.js"></script>
        <script src="/scripts/myTools.js"></script>
        <script>
            $(document)
                .ready(function () {
                    // create sidebar and attach to menu open
                    $('.ui.sidebar')
                        .sidebar('attach events', '.toc.item')
                    ;

                    $('.ui.checkbox')
                        .checkbox()
                    ;
                    $('.message .close')
                        .on('click', function () {
                            $(this)
                                .closest('.message')
                                .transition('fade')
                            ;
                        })
                    ;

                })
            ;

        </script>
        <head>
<body>


    <!-- Sidebar Menu -->
    <div class="ui vertical inverted sidebar menu">
        <?php showmenu(true); ?>
    </div>


    <!-- Page Contents -->
<div class="pusher">
    <div class="ui fixed menu">
        <div class="ui container">
            <?php showmenu(false) ?>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br><br><br>
    <div class="ui two column doubling stackable grid container">
        <div class="row">
            <h1 class="ui dividing header">
                下载客户端
            </h1>
        </div>
        <div class="column">
            <h2 class="ui dividing header">安卓客户端</h2>
            <a href="/download/zhuoxinsocialapp.apk">点击下载</a>
        </div>


        <div class="column">
            <h2 class="ui dividing header">苹果客户端（需越狱）</h2>
            <a href="/download/zhuoxinsocialapp.ipa">点击下载</a>
        </div>

    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<?php
require_once "footer.php";
