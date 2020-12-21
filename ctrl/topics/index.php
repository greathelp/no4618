<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
include_once("../db.php");


//ショップID　単独店舗は0固定
$shop_id = 0;

$conn = DB_Conn();

//記事リスト　取得
$sql = "SELECT * FROM " . TBL_TOPIC_NEWS . " WHERE shop_id =" . $shop_id . " and enable = 1 order by date1 DESC";
$result = $conn->query($sql);
$items = $result->fetchAll();
$cnt = $result->rowCount();

//Pager設定
$options = array(
  'totalItems' => $cnt,
  "delta" => 10,
  "perPage" => 10,
  "itemData" => $items
);
$pager = Pager::factory($options);

//ナビゲーション
$navi = $pager->getLinks();
//表示ページ分のデータ取得
$pagedat = $pager->getPageData();

//DUM
$DUM = date("His");
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>記事管理</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            font-family: 'Lucida Grande',
            'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3',
            Meiryo, メイリオ, sans-serif;
        }

    </style>
</head>
<body>
<div class="container">
    <table class="table table-hover" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
            <td colspan="4" align="center"><h2>おおたかの森 様</h2></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><h3>現在の「おしらせ」記事一覧</h3></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><a class="btn btn-primary" href="edit.php?mode=new">記事の新規追加</a></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><a class="btn btn-primary" href="../menu.php">メニューへ戻る</a></td>
        </tr>
        <tr>
            <td align="center">番号</td>
            <td align="center">日付</td>
            <td align="center">記事タイトル</td>
            <td align="center">削除</td>
        </tr>
      <?PHP
      foreach ($pagedat as $val => $row) {
        $datetime = $row['date1'];
        ?>
          <tr height="30" style="font-size:0.85em;">
              <td width="50" align="center" style="vertical-align: middle;"><a
                          href="edit.php?mode=edit&id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
              <td width="100" align="center" style="vertical-align: middle;"><?= $datetime ?></td>
              <td align="center" style="vertical-align: middle;font-size: 1.5em;"><a
                          href="edit.php?mode=edit&id=<?= $row['id'] ?>"><?= $row['title'] ?></a></td>
              <td width="50" align="center"><a class="btn btn-danger" href="del.php?id=<?= $row['id'] ?>">削除</a></td>
          </tr>
        <?php
      } //foreach
      ?>
        <tr>
            <td align="center" colspan="4"><?= $navi['all'] ?></td>
        </tr>
        <tr>
            <td height="30" colspan="4" align="center"><a class="btn btn-primary" href="../menu.php">メニューへ戻る</a></td>
        </tr>
    </table>
</div>
</body>
</html>