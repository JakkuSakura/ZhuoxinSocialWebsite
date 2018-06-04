<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/3/5
 * Time: 15:46
 */
require_once "Database.php";
require_once "User.php";

class Topic
{
    public function __construct($qr)
    {
        $this->topics = [];
        if (is_string($qr))
            echo "$qr<br />";
        else
            while ($obj = mysqli_fetch_object($qr)) {
                $obj->text = htmlspecialchars(urldecode($obj->text));
                $this->topics[] = $obj;
            }
    }

    public function getTopicByID($id)
    {
        foreach ($this->topics as $topic) {
            if ($topic->id == $id)
                return $topic;
        }
        return self::getNullTopic();
    }

    static public function getReply()
    {
        $rs = Database::query('topic', "`reply_to`=" . User::getLoginUser()->id);
        $tp = new Topic($rs);
        return $tp;
    }

    static public function getSent()
    {
        $rs = Database::query('topic', "`poster`=" . User::getLoginUser()->id);
        $tp = new Topic($rs);
        return $tp;
    }

    static public function getReplyAndSent()
    {
        $rs = Database::query('topic', "`reply_to`=" . User::getLoginUser()->id . " or `poster`=" . User::getLoginUser()->id);
        $tp = new Topic($rs);
        return $tp;
    }

    static public function getTopics($show_at)
    {
        $rs = Database::query('topic', "`show_at`=" . $show_at);

        $tp = new Topic($rs);

        return $tp;
    }

    static public function getNullTopic()
    {
        $rs = Database::query('topic', "`id`=0");
        $tp = new Topic($rs);
        return $tp;
    }
}