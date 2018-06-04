<?php
require_once "configure.php";
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/22
 * Time: 22:24
 */
require_once ROOT . "/qqlogin.php";

$qq = new \Component\QQ_LoginAction();
$qq->qq_login();