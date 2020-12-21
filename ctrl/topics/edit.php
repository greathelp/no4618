<?php
/*
 * トピックボード
 * 新規・編集
 * 2015.9.29
 */
session_start();
//
//DB情報読み込み
include_once("../db.php");

$mode = "";
if (isset($_GET['mode'])) {
  if ($_GET['mode'] == 'new') {
    $mode = "new";
  }
} else {
  header("Location: ../menu.php");
  exit();
}
$title = $mode == "new" ? "新規登録" : "更新";
$body = "";

if ($mode != "new") {
  //新規じゃなければ保存データ取得
  $id = 1;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  //DB接続
  $conn = DB_Conn();
  $sql = "select * from " . TBL_TOPIC_NEWS . " where enable = 1 and id = " . $id;

  $result = $conn->query($sql);
  $rows = $result->fetch(PDO::FETCH_ASSOC);

  $title = $rows['title'];
  $date = $rows['date1'];

  $year = date("Y", strtotime($date));
  $month = date("m", strtotime($date));
  $day = date("d", strtotime($date));

  $body = base64_decode($rows['body']);
  $body = str_replace('\\', '', $body);
  $body = str_replace('\"', '', $body);
} else {
  //新規時
  $id = 0; //新規は０
  //今日の日付
  $year = date("Y");
  $month = date("m");
  $day = date("d");
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>記事編集</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style type="text/css">
        body {
            font-size: 13px;
            background-color: #fff;
            color: #000;
        }

        #editor1 {
            width: 100%;
            background-color: #fff;
            color: #000;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="reg.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
        <table class="table table-hover" align="center" style="width: 600px;margin-top:30px;">
            <tr>
                <td align="center" style="font-size:1.5em;font-weight:bold;">
                    「お知らせ」記事　追加・編集
                </td>
            </tr>
            <tr>
                <td align="center" style="padding:1em 0;">
                    <a class="btn btn-primary" href="./index.php">戻　る</a>
                </td>
            </tr>
            <tr>
                <td align="left">
                    記事のタイトル<br>
                    <input class="form-control" type="text" name="title" id="title" value="<?= $title ?>"/>
                </td>
            </tr>
            <tr>
                <td>記事の日時
                    <div class="form-inline">
                        <input class="form-control" style="text-align:right;" size="5" type="text" name="year" id="year"
                               value="<?= $year ?>"/>年
                        <input class="form-control" style="text-align:right;" size="3" type="text" name="month"
                               id="month" value="<?= $month ?>"/>月
                        <input class="form-control" style="text-align:right;" size="3" type="text" name="day" id="day"
                               value="<?= $day ?>"/>日
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <textarea class="editor" cols="30" id="editor1" name="editor1" rows="10"
                    ><?= $body ?></textarea>
                </td>
            </tr>
            <tr>
                <td align="center"><input class="btn btn-info" type="submit" value="記事を登録する"/></td>
            </tr>
            <tr>
                <td align="center" style="padding:1em 0;">
                    <a class="btn btn-primary" href="./index.php">戻　る</a>
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>