<?php
/*
 * エデン　ニュース
 * 新規登録・編集更新
 * 2015.9.29
 */
require_once '../db.php';

$id = 1;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../menu.php");
    exit();
}

//日付の整形
$date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];

//タイトル
$title = $_POST['title'];

//登録する
$conn = DB_Conn();

if ($id == 0) {
    $sql = "insert into " . TBL_TOPIC_NEWS . " set ";
    $sql .= "title = '" . $title . "' , ";
    $sql .= "date1 = '" . $date . "' , ";
    $sql .= "body = '" . base64_encode($_POST['editor1']) . "' , ";
    $sql .= "enable = 1";
} else {
    $sql = "update " . TBL_TOPIC_NEWS . " set ";
    $sql .= "title = '" . $title . "' , ";
    $sql .= "date1 = '" . $date . "' , ";
    $sql .= "body = '" . base64_encode($_POST['editor1']) . "' , ";
    $sql .= "enable = 1";
    $sql .= " where id = " . $id;
}


//ChromePhp::log($sql);

$result = $conn->prepare($sql);
$result->execute();

header('Location: ./index.php?DUM=' . date('YmdHis'));
?>