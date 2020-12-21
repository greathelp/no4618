<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/common/db.php";

$conf = array('mode'=>0777,'lineFormat' => '%1$s %3$s %2$s [%6$s] - %4$s','timeFormat'=>'%Y/%m/%d %H:%M:%S');
$log = Log::singleton('file', $_SERVER["DOCUMENT_ROOT"].'/log/common/' . date("Y") . "/" . date("m") . "/" . date("Ymd") . "_getInfo.txt", '', $conf, PEAR_LOG_DEBUG);
$log->log( "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝　開始　＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝" );
$log->log( "REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] );
$log->log( "gethostbyaddr:" . gethostbyaddr( $_SERVER["REMOTE_ADDR"] ) );

$data = [
  "tool" => filter_input(INPUT_POST, "tool"),
];
$log->log("↓↓↓↓↓↓↓↓↓↓↓　data　↓↓↓↓↓↓↓↓↓↓↓↓");
$log->log($data);
$log->log(getToolInfo($data["tool"]));

$ret = [
  "tool" => getToolInfo($data["tool"]),
];

echo json_encode($ret);