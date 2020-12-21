<?php
/*
 * スケジュール表示・編集ページ
 * 時間単位版
 * 2014.3.17
 */
//ログイン済みのチェック
//include_once ("../lgchk.php");
require_once '../db.php';

$year1 = date("Y");
$month1 = date("n");

//翌月だと$monthにnextが入ってくる
$month = filter_input(INPUT_GET, "month");

//翌月フラグがちゃんと入ってるか
if ($month == "next") {
  if ($month1 == 12) {
    //１２月なら月は１にして年を増やす
    $year1 = $year1 + 1;
    $month1 = 1;
  } else {
    //１２月未満なら年はそのまま、月だけ増やす
    $month1 = $month1 + 1;
  }
}


//スタート
$start_day = $year1 . "-" . $month1 . "-1";
// 処理年月の最終日を取得
$end_day = date("t", mktime(0, 0, 0, $month1, 1, $year1));

//関数類読み込み
include_once("funcSchedule.php");

//DB接続
$conn = DB_Conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title></title>
    <link rel="stylesheet" href="calendar.css">

    <style type="text/css">
        body {
            background-repeat: repeat;
            font-size: 13px;
        }

        .inp_time {
            width: 5em;
            text-align: right;
        }

        .calendar {
            width: 100%;
        }

    </style>
</head>
<body>
<form action="schedule_reg.php" method="post">
    <table width="500" border="0" align="center" cellpadding="0" cellspacing="1" style="background-color:#000;">
        <tr class="title02">
            <td align="center" bgcolor="#F0F8ED" class="title01">おおたかの森 カレンダー編集</td>
        </tr>
        <tr>
            <td align="left" bgcolor="#FFFFFF" class="warning" style="padding:5px 0px 5px 15px;line-height:1.3em;">
                ※「前月」「翌月」に移行する時は保存してからにして下さい。<br>
                <span style="color: red;">※「出勤」にチェックが入ってないとその日の予定は保存されません！</span>
            </td>
        </tr>
        <tr bgcolor="#FFFFFF" class="title02">
            <td height="30" align="center">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="top"><?php calendar(0, $year1, $month1); ?></td>
                    </tr>
                </table>
                <br/>
            </td>
        </tr>
        <tr bgcolor="#FFFFFF" class="title02">
            <td height="60" align="center" style="line-height:20px;">
            </td>
        </tr>
        <tr bgcolor="#FFFFFF" class="title02">
            <td height="30" align="center"><input type="submit" name="button" id="button" value="以上で登録する"/></td>
        </tr>
        <tr bgcolor="#FFFFFF" class="title02">
            <td height="30" align="center"><a href="../menu.php">メニューへ戻る</a></td>
        </tr>
    </table>
</form>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(function () {
        $(".change_radio").change(function () {
            console.log("come");
            var dt = $(this).attr("name");
            var val = $(this).val();

            //サーバへ送信してDB書き換え
            $.ajax({
                url: 'reg.php',
                type: 'POST',
                data: {
                    'dt': dt,
                    'val': val
                }
            })
            // Ajaxリクエストが成功した時発動
                .done((data) => {
                    console.log(data);
                })
                // Ajaxリクエストが失敗した時発動
                .fail((data) => {
                    alert("理由不明ですが更新失敗");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always((data) => {

                });
        });

    });
</script>
</body>
</html>