<?php
require_once '../db.php';
require_once '../../vendor/autoload.php';

$conf = array(
  'mode' => 0777,
  'timeFormat' => '%Y/%m/%d %H:%M:%S'
);
$log = Log::singleton('file', $_SERVER["DOCUMENT_ROOT"] . '/log/ctrl/calendar/' . date("Y") . "/" . date("m") . "/" . date("Ymd") . "_reg.txt", 'TEST', $conf, PEAR_LOG_DEBUG);


$log->log("＝＝＝＝＝開始＝＝＝＝＝");
$log->log("REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"]);
$log->log("gethostbyaddr:" . gethostbyaddr($_SERVER["REMOTE_ADDR"]));
$log->log("HTTP_REFERER:" . $_SERVER["HTTP_REFERER"]);

$dt = filter_input(INPUT_POST, "dt");
$log->log("dt1" . $dt);
$dt = str_replace("chk_", "", $dt);//「chk_」を削除
$log->log("dt2" . $dt);

$val = filter_input(INPUT_POST, "val");
$log->log("val" . $val);

$conn = DB_Conn();
$sql = "delete from " . TBL_SCHEDULE . " WHERE cal_date = :dt ";
$result = $conn->prepare($sql);
$result->bindValue(":dt", $dt);
$result->execute();

if ($val != "0") {
  $sql = "INSERT INTO " . TBL_SCHEDULE . " SET cal_date = :dt , ";
  $sql .= "status = :st ";
  $result2 = $conn->prepare($sql);
  $result2->bindValue(":dt", $dt);
  $result2->bindValue(":st", $val);
  $result2->execute();
}

echo "OK";
