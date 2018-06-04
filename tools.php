<?php
/**
 * Created by PhpStorm.
 * User: QiuJiangkun
 * Date: 2018/3/5
 * Time: 14:50
 */
function do_post_request($url, $data, $optional_headers = null)
{
    $data = http_build_query($data);
    $params = array('http' => array(
        'method' => 'POST',
        'content' => $data
    ));
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
}

function getIP() /*获取客户端IP*/
{
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (@$_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (@$_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (@getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (@getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (@getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

class Message
{
    public function __construct($state, $msg, $type)
    {
        $this->state = $state;
        $this->msg = $msg;
        $this->type = $type;

    }

    public function toStr()
    {
        switch ($this->type) {
            case 'plain':
                return $this->msg;
                break;
            case 'json':
                return json_encode($this);
                break;
            default:
                return "NULL";
        }
    }

    static public function sendmsg($state, $msg, $type)
    {
        die((new Message($state, $msg, $type))->toStr());
    }
}

function sendmsg($state, $msg, $type = "json")
{
    Message::sendmsg($state, $msg, $type);
}

function getFile($url, $save_dir = '', $filename = '', $type = 0)
{
    if (trim($url) == '') {
        return false;
    }
    if (trim($save_dir) == '') {
        $save_dir = './';
    }
    if (0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return false;
    }
    //获取远程文件所采用的方法
    if ($type) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $content = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $content = ob_get_contents();
        ob_end_clean();
    }
    //echo $content;
    $size = strlen($content);
    //文件大小
    $fp2 = @fopen($save_dir . $filename, 'a');
    fwrite($fp2, $content);
    fclose($fp2);
    unset($content, $url);
    return array(
        'file_name' => $filename,
        'save_path' => $save_dir . $filename,
        'file_size' => $size
    );
}

function cut_str($string, $start = 0, $sublen, $sign = true, $code = 'UTF-8')
{
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if ($sign and count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';

        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129) $i++;
        }
        if ($sign and strlen($tmpstr) < $strlen) $tmpstr .= "...";
        return $tmpstr;
    }
}

function url_set_value($url, $key, $value)
{
    $a = explode('?', $url);
    $url_f = $a[0];
    $query = $a[1];
    parse_str($query, $arr);
    $arr[$key] = $value;
    return $url_f . '?' . http_build_query($arr);
}

function GetCurUrl()
{
    if (!empty($_SERVER["REQUEST_URI"])) {
        $scriptName = $_SERVER["REQUEST_URI"];
        $nowurl = $scriptName;
    } else {
        $scriptName = $_SERVER["PHP_SELF"];
        if (empty($_SERVER["QUERY_STRING"])) {
            $nowurl = $scriptName;
        } else {
            $nowurl = $scriptName . "?" . $_SERVER["QUERY_STRING"];
        }
    }
    return $nowurl;
}

// 你能写得再麻烦点吗？
function isMobile()
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) ||
        preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4)));
}

class Time
{
    //返回年份 time：时间格式为时间戳  2013-3-27
    static function getyear($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        if ($type == 1) {
            return $year = date("y", $time); //返回两位的年份 13
        } else {
            return $year = date("Y", $time); //返回四位的年份 2013
        }
    }

    //返回当前时间的月份 time：时间格式为时间戳 2013-3-27
    static function getmonth($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        switch ($type) {
            case 1:
                $month = date("n", $time);//返回格式 3
                break;
            case 2:
                $month = date("m", $time);//返回格式 03
                break;
            case 3:
                $month = date("M", $time);//返回格式 Mar
                break;
            case 4:
                $month = date("F", $time);//返回格式 March
                break;
            default:
                $month = date("n", $time);
        }
        return $month;
    }

    //返回当前时间的天数 time：时间格式为时间戳 2013-3-4
    static function getday($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        if ($type == 1) {
            $day = date("d", $time);//返回格式 04
        } else {
            $day = date("j", $time);//返回格式 4
        }
        return $day;
    }

    //返回当前时间的小时  2010-11-10 1:19:21 20:19:21
    static function gethour($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        switch ($type) {
            case 1:
                $hour = date("H", $time);//格式： 1 20
                break;
            case 2:
                $hour = date("h", $time);//格式  01 08
                break;
            case 3:
                $hour = date("G", $time);//格式  1 20
                break;
            case 4:
                $hour = date("g", $time);//格式  1 8
                break;
            default :
                $hour = date("H", $time);
        }
        return $hour;
    }

    //返回当前时间的分钟数 1:9:18
    static function getminute($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        $minute = date("i", $time); //格式  09
        return $minute;
    }

    //返回当前时间的秒数  20:19:01
    static function getsecond($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        $second = date("s", $time); //格式  01
        return $second;
    }

    //返回当前时间的星期数
    static function getweekday($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($time == null) {
            $time = time();
        }
        if ($type == 1) {
            $weekday = date("D", $time);//格式  Sun
        } else if ($type == 2) {
            $weekday = date("l", $time); //格式 Sunday
        } else {
            $weekday = date("w", $time);//格式 数字表示 0--6
        }
        return $weekday;
    }

    //比较两个时间的大小 格式 2013-3-4 8:4:3
    static function compare($time1, $time2)
    {
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        if ($time1 >= $time2) {  //第一个时间大于等于第二个时间 返回1 否则返回0
            return 1;
        } else {
            return -1;
        }
    }

    //比较两个时间的差值
    static function diffdate($time1 = null, $time2 = null)
    {
        if (is_string($time1))
            $time1 = strtotime($time1);
        if (is_string($time2))
            $time2 = strtotime($time2);
        if ($time1 == null) {
            $time1 = time();
        }
        if ($time2 == null) {
            $time2 = time();
        }


        $diff = abs($time1 - $time2);

        $day = floor($diff / 86400);
        $hour = floor(($diff % 86400) / 3600);
        $minute = floor(($diff % 3600) / 60);
        $second = floor(($diff % 60));
        $diffTime = ($day ? $day . '天' : "") . ($hour ? $hour . '小时' : "") . ($minute ? $minute . '分钟' : "") . ($second ? $second . '秒' : "");
        return $diffTime;

    }

    //比较两个时间的差值， 大约值
    static function diffdate_proximate($time1 = null, $time2 = null)
    {
        if (is_string($time1))
            $time1 = strtotime($time1);
        if (is_string($time2))
            $time2 = strtotime($time2);
        if ($time1 == null) {
            $time1 = time();
        }
        if ($time2 == null) {
            $time2 = time();
        }

        $diff = abs($time1 - $time2);
        $a = [
            [86400 * 365, "年"],
            [86400 * 30, "月"],
            [86400, "天"],
            [3600, "小时"],
            [60, "分"],
            [1, "秒"]];


        foreach ($a as $item) {
            $b = $item[0];
            $v = $diff / $b;
            if ($v >= 0.9) {
                return floor($v + 0.1) . $item[1] . "前";
            }
        }
        return "刚刚";

    }

    //返回 X年X月X日
    static function buildDate($time = null, $type = null)
    {
        if (is_string($time))
            $time = strtotime($time);
        if ($type == 1) {
            $longDate = Time::getyear($time) . '年' . Time::getmonth($time) . '月' . Time::getday($time) . '日';
        } else {
            $longDate = Time::getyear($time) . '年' . Time::getmonth($time) . '月' . Time::getday($time) . '日' . Time::gethour($time) . ':' . Time::getminute($time) . ':' . Time::getsecond($time);
        }
        return $longDate;
    }
}

function partition(&$A, $l, $h, $k)
{
    $x = $A[$l + rand() % ($h - $l + 1)]->$k;

    for ($i = $l - 1, $j = $l; $j <= $h; ++$j)
        if ($A[$j]->$k < $x && ++$i != $j) {
            $t = $A[$i];
            $A[$i] = $A[$j];
            $A[$j] = $t;
        }

    return $i;
}

function quicksort(&$A, $l, $h, $k)
{
    if ($l < $h) {
        if ($h - $l <= 20) {
            for ($i = $l; $i <= $h; ++$i) {
                $x = $A[$i];
                for ($j = $i - 1; $j >= 0 && $A[$j]->$k > $x->$k; --$j)
                    $A[$j + 1] = $A[$j];
                $A[$j + 1] = $x;
            }
        } else {
            $p = partition($A, $l, $h, $k);
            quicksort($A, $l, $p - 1, $k);
            quicksort($A, $p + 1, $h, $k);
        }
    }
}

function get_variable_name(&$var, $scope = null)
{

    $scope = $scope == null ? $GLOBALS : $scope;
    $tmp = $var;

    $var = 'tmp_value_' . mt_rand();
    $name = array_search($var, $scope, true);

    $var = $tmp;
    return $name;
}