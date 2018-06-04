<?php
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/23
 * Time: 22:22
 */
function open_session()
{
    if(!isset($_SESSION))
    {
        session_start();
    }
}
