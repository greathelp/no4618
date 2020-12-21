<?php

/*
 * 記事の削除
 * 実際はフラグを立てて消しはしない
 * 2015.9.29 Seiji Kato
 */
session_start();
//
//DB情報読み込み
include_once ("../db.php");
$id = 1;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../menu.php");
    exit();
}

//登録する
$conn = DB_Conn();

$sql = "update " . TBL_TOPIC_NEWS . " set ";
$sql .= "enable = 0 ";
$sql .= "where id = " . $id;

$result = $conn->prepare($sql);
$result->execute();

header('Location: ./index.php?DUM=' . date('YmdHis'));

?>
