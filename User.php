<?php
/**
 * Created by PhpStorm.
 * User: Rocon
 * Date: 2018/2/2
 * Time: 22:05
 */
require_once 'Database.php';
require_once 'sessionHandle.php';
open_session();

class User
{
    public static function getUser($by, $wt)
    {
        $wt = addslashes($wt);
        $qr1 = Database::query("user", "{$by}=\"{$wt}\"");
        if (is_string($qr1))
            die($qr1);
        if (mysqli_num_rows($qr1))
            return new User(mysqli_fetch_object($qr1));
        else
            return self::getNullUser();
    }

    public static function getLoginUser()
    {
        if (!self::isLoggedIn())
            return self::getNullUser();
        static $loggeduser = null;
        if ($loggeduser)
            return $loggeduser;
        else
            return $loggeduser = self::getUser('id', $_SESSION['olid']);
    }

    public static function userLogin($email, $pswd)
    {
        $email = addslashes($email);
        $qr1 = Database::query("user", "email=\"{$email}\" and password=\"{$pswd}\"");
        if (mysqli_num_rows($qr1)) {
            $qr = mysqli_fetch_object($qr1);
            return new User($qr);
        } else
            return self::getNullUser();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['online']) && $_SESSION['online'];
    }

    public function __construct($obj)
    {

        $this->id = $obj->id;
        $this->email = $obj->email;
        $this->gender = $obj->gender;
        $this->relationship = $obj->relationship_status;
        $this->img_path = $obj->img_path;
        $this->wanna_up = $obj->wanna_up;
        $this->wanna_down = $obj->wanna_down;
        $this->last_login = $obj->last_login;
        $this->signup_date = $obj->signup_date;
        $this->intr_view_times = $obj->intr_view_times;
        $this->is_admin = $obj->is_admin;
        $this->birthday = $obj->birthday;
        $this->banned = $obj->banned;

        $this->realname = htmlspecialchars(urldecode($obj->realname));
        $this->nickname = htmlspecialchars(urldecode($obj->nickname));
        $this->self_introduction = htmlspecialchars(urldecode($obj->self_introduction));
        $this->province = htmlspecialchars(urldecode($obj->province));
        $this->city = htmlspecialchars(urldecode($obj->city));
        $this->habits = htmlspecialchars(urldecode($obj->habits));
        $this->obj = $obj;

        $this->age = $this->calcAge($this->birthday);
    }

    public function calcAge($birthday) {
        $iage = 0;
        if (!empty($birthday)) {
            $year = date('Y',strtotime($birthday));
            $month = date('m',strtotime($birthday));
            $day = date('d',strtotime($birthday));

            $now_year = date('Y');
            $now_month = date('m');
            $now_day = date('d');

            if ($now_year > $year) {
                $iage = $now_year - $year - 1;
                if ($now_month > $month) {
                    $iage++;
                } else if ($now_month == $month) {
                    if ($now_day >= $day) {
                        $iage++;
                    }
                }
            }
        }
        return $iage;
    }

    public function getFriends()
    {
        $rs = Database::query('friendship', "user1id={$this->id} or user2id={$this->id}");

        $this->friends = [];
        $this->friendsnum = 0;
        while ($obj = mysqli_fetch_object($rs)) {
            $this->friends[] = $obj;
            if ($obj->state == 1)
                $this->friendsnum = $this->friendsnum + 1;
        }
    }

    public function getReceivedMessages()
    {
        if (isset($this->revmsg))
            return $this->revmsg;
        $rs = Database::query('message', "user1id={$this->id}");

        $this->revmsg = [];
        while ($obj = mysqli_fetch_object($rs)) {
            $obj->text = htmlspecialchars(urldecode($obj->text));
            $this->revmsg[] = $obj;
        }

        return $this->revmsg;
    }

    public function getSentMessages()
    {
        if (isset($this->sntmsg))
            return $this->sntmsg;

        $rs = Database::query('message', "user2id={$this->id}");

        $this->sntmsg = [];
        while ($obj = mysqli_fetch_object($rs)) {
            $obj->text = htmlspecialchars(urldecode($obj->text));
            $this->sntmsg[] = $obj;
        }
        return $this->sntmsg;
    }

    public function getMessages()
    {

        return array_merge($this->getSentMessages(), $this->getReceivedMessages());
    }

    public function getCommits()
    {
        $rs = Database::query('commit', "user1id={$this->id}");

        $this->commits = [];
        while ($obj = mysqli_fetch_object($rs)) {
            $obj->text = htmlspecialchars(urldecode($obj->text));
            $this->commits[] = $obj;
        }
    }
    public function isFriend($with_id)
    {
        if (!$this->valid())
            return false;
        $friendship = $this->getFriendship($with_id);
        return isset($friendship->id) && $friendship->id > 0;
    }
    public function getFriendship($with_id)
    {
        $rst = Database::query('friendship', "user1id={$with_id} and user2id={$this->id}");
        if (!mysqli_num_rows($rst))
            $rst = Database::query('friendship', "user2id={$with_id} and user1id={$this->id}");
        if (mysqli_num_rows($rst))
            return mysqli_fetch_object($rst);
        else
            return mysqli_fetch_object(Database::query('friendship', "id=0"));
    }
    public function valid()
    {
        return $this->id > 0;
    }

    public static function getNullUser()
    {
        return self::getUser('id', 0);
    }
}
