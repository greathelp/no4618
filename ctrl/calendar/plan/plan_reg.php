<?php

/*
 * プラン登録
 */
include_once '../../db.php';

$gid = filter_input(INPUT_POST, "gid");
$date = filter_input(INPUT_POST, "date");
$start = filter_input(INPUT_POST, "start_time");
$plan = filter_input(INPUT_POST, "plan");
$stext = filter_input(INPUT_POST, "stext");

$conn = DB_Conn();
//コメントを入力しておく
$sql = "update " . TBL_SCHEDULE . " set s_text = :text ";
$sql.= "where gid = :gid and s_date = :sdate";
$rslt_comment = $conn->prepare($sql);
$rslt_comment->bindValue("gid", $gid);
$rslt_comment->bindValue("text", $stext);
$rslt_comment->bindValue("sdate", $date);
$rslt_comment->execute();

if ($start != "" and $plan != "") {
    //プラン登録
    $sql = "insert into " . TBL_PLAN . " set gid = " . $gid . " , ";
    $sql .= " date = '" . $date . "' , ";
    $sql .= "start_time = '" . $start . "' , ";
    $sql .= "plan = " . $plan;
    $rslt = $conn->prepare($sql);
    $rslt->execute();
}

header("Location:index.php?date=" . $date);
