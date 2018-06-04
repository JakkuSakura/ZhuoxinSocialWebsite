<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/19
 * Time: 20:24
 */
define("ROOT", dirname(dirname(__FILE__)) . "/");
require_once ROOT . "/header.php";
require_once ROOT . "/Database.php";
require_once ROOT . "/User.php";
require_once ROOT . "/tools.php";
echo <<< TAG
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>卓信社交</title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <link rel="stylesheet" href="css/ydui.css?rev=@@hash"/>
    <link rel="stylesheet" href="css/demo.css"/>
    <script src="/scripts/jquery-3.3.1.min.js"></script>
    <script src="/scripts/adblock.js"></script>
    <script src="js/ydui.flexible.js"></script>
    <script src="js/ydui.js"></script>
    <script src="/scripts/navfix.js"></script>
    <script src="/scripts/myTools.js"></script>
    
    <script>(!YDUI.device.isMobile && navigator.userAgent.indexOf('Firefox') >= 0) && YDUI.dialog.alert('PC端请使用谷歌内核浏览器查看！');</script>
    
    <script>$(document).ready(function(e) { $("#navMain").navfix(0,999); });</script>
<head>
<body>

TAG;
?>