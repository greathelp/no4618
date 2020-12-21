<?php
/*
 * スケジュール表示・編集ページ
 */

//ログイン済みのチェック
include_once ("../lgchk.php");
require_once "../db.php";

$date = date("Y-m-d");
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>日付選択</title>
        <link rel="stylesheet" href="../../js/jquery-ui-1.10.3/themes/base/jquery-ui.css">
    </head>
    <body>
        <div style="width:500px;margin: 0 auto;margin-top:200px;">
            スケジュールを入力する日付を選択して「開始」をクリックしてください<br>
            <input type="text" name="datepicker" id="datepicker" value="<?= $date ?>">
            <button type="button" onClick="go();">開始</button>
        </div>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="../../js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
        <script src="../../js/jquery-ui-1.10.3/ui/i18n/jquery.ui.datepicker-ja.js"></script>
        <script>
                $(function () {
                    $("#datepicker").datepicker();
                });

                function go() {
                    var date1 = $("#datepicker").val();
                    location.href = "./plan/index.php?date=" + date1;
                }
        </script>
    </body>
</html>