<?php

class Database
{
    public static function query($where, $cond)
    {
        return Database::SQLquery("SELECT * FROM `{$where}` where {$cond}");
    }

    public static function update($where, $wt, $st, $cond)
    {
        $qry = "UPDATE `{$where}` SET `{$wt}` = '{$st}' WHERE {$cond}";
        $err = Database::SQLquery($qry);
//        if (is_string($err))
//        {
//            echo "$qry<br>\n$err<br>\n";
//        }
    }

    public static function updateItems($where, $wtgroup, $stgroup, $cond)
    {

        $SENTENCE = "`{$wtgroup[0]}`='{$stgroup[0]}'";
        for ($i = 1; $i < count($wtgroup); ++$i) {
            $SENTENCE .= ",`{$wtgroup[$i]}`='{$stgroup[$i]}' ";
        }

        $query = "UPDATE `{$where}` SET " . $SENTENCE . "WHERE {$cond}";
        //echo $query;
        return Database::SQLquery($query);
    }

    public static function SQLquery($query)
    {

        //self::print_stack_trace();
        //echo "<script>console.log(\"" . addslashes($query) . "\")</script>";
        //echo $query."\n";
        if ($_SERVER['HTTP_HOST'] == "zhuoxinsocial.top")
            $con = mysqli_connect('localhost:3306', 'qjk_bird', 'bird1234', 'qjk_bird');
        else
            $con = mysqli_connect('localhost:3306', "root", "", "test");
        if (mysqli_connect_errno()) {
            return mysqli_connect_errno();
        }

        $result = mysqli_query($con, $query);
        if (!$result) {
            return mysqli_error($con);
        }
        $con->close();
//        if (is_object($result))
//            echo "<script>console.log('". serialize($result) . "')</script>";
//        else
//            echo "<script>console.log('{$result}')</script>";
        return $result;
    }

    public static function print_stack_trace()
    {
        $array = debug_backtrace();
        //print_r($array);//信息很齐全
        unset($array[0]);
        $html = "";
        foreach ($array as $row) {
            $html .= $row['file'] . ':' . $row['line'] . '行,调用方法:' . $row['function'] . "<p>";
        }
        return $html;
    }
}

