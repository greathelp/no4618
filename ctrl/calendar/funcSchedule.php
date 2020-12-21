<?php

require_once '../db.php';

function calendar($gid, $year, $month)
{
  $nowday = date("Ynj"); //　今日の日付
  //今月の初めの曜日//
  $start_youbi = date("w", mktime(0, 0, 0, $month, 1, $year));
  $tuki_owari = date("t", mktime(0, 0, 0, $month, 1, $year)); //　その月の終りの日数


  //スケジュール取り出し
  $conn = DB_Conn();
  $sql = "select * from " . TBL_SCHEDULE . " where cal_date BETWEEN :sdate and :edate order by cal_date DESC";

  $result = $conn->prepare($sql);
  $result->bindValue(":sdate", $year . "-" . $month . "-1");
  $result->bindValue(":edate", $year . "-" . $month . "-" . $tuki_owari);
  $result->execute();
  $sche = array();
  $tmp_dates = array();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $tmp_dates[] = strtotime($row['cal_date']);
    $sche[] = array(
      'date' => $row['cal_date'],
      'status' => $row['status'],
    );
  }

  //	前月・翌月を出すかどうか
  $next_month_text = '&nbsp';
  $forward_month_text = '&nbsp;';
  if ($month == date("n")) {
    $next_month_text = '<a href="schedule_edit.php?month=next">翌　月</a>';
  } else {
    $forward_month_text = '<a href="schedule_edit.php?month=forward">前　月</a>';
  }

  $title_text = '' . $forward_month_text . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:1.5em;">' . $year . '年' . $month . '月</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $next_month_text;
//////////////フォーム部分///////////////////////
  echo <<<EOT
<input type="hidden" name="year" value="{$year}">
<input type="hidden" name="month" value="{$month}">
{$title_text}
<table class="calendar">
<tr>
<th class="nitiyoubi">日</th>
<th>月</th><th>火</th><th>水</th><th>木</th>
<th>金</font></th>
<th class="doyoubi">土</font></th>

</tr>
<tr>
EOT;
  $cnt = 1;
  $orikaesi = 0;
  if ($start_youbi != 0) {//月曜始まりでなければ空セル発射！
    for ($i = 1; $i <= $start_youbi; $i++) {
      echo "<td>&nbsp;</td>";
      $orikaesi++;
    }
  }
  while ($cnt <= $tuki_owari) {
    $tmp_nowday = $year . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $cnt);
    if ($nowday == $year . $month . $cnt) {
      $td = "honjitu"; //本日の色
    } elseif ($orikaesi == 6) {
      $td = "doyoubi"; //土曜日の色
    } elseif ($orikaesi == 0) {
      $td = "nitiyoubi"; //日曜日の色
    } else {
      $td = "heijitu"; //平日の色
    }
    //その日のスケジュールがあるかをチェック
    $ret = in_array(strtotime($tmp_nowday), $tmp_dates);
    $tmp_check[0] = "";
    $tmp_check[1] = "";
    $tmp_check[2] = "";
    $tmp_check[3] = "";
    if ($ret) {
      $tmp_key = array_search(strtotime($tmp_nowday), $tmp_dates);
      $tmp_check[$sche[$tmp_key]["status"]] = " CHECKED=\"CHECKED\"";
    } else {
      $tmp_check[0] = " CHECKED=\"CHECKED\"";
    }

    echo '<td class="' . $td . '" style="text-align:center">' . $cnt . '<hr/>';
    echo '<div style="text-align:left">';
    echo '<input class="change_radio" type="radio" name="chk_' . $tmp_nowday . '" value="0" ' . $tmp_check[0] . '>通常<br>';
    echo '<input class="change_radio" type="radio" name="chk_' . $tmp_nowday . '" value="1" ' . $tmp_check[1] . '>休診日<br>';
    echo '<input class="change_radio" type="radio" name="chk_' . $tmp_nowday . '" value="2" ' . $tmp_check[2] . '>午後短<br>';
    echo '<input class="change_radio" type="radio" name="chk_' . $tmp_nowday . '" value="3" ' . $tmp_check[3] . '>矯正';
    echo '</div>';
//    echo '<input type="checkbox" name="chk_' . $tmp_nowday . '" ' . $tmp_check . '">出勤';
//    echo '<input class="inp_time" type="text" name="start_' . $tmp_nowday . '" value="' . trim($tmp_start) . '"><br>';
//    echo '<input class="inp_time" type="text" name="end_' . $tmp_nowday . '" value="' . trim($tmp_end) . '">';
    echo '</td>';
    $cnt++;
    $orikaesi++;
    if (($orikaesi == 7) && ($tuki_owari != $cnt - 1)) {
      echo "</tr><tr>"; //折り返し
      $orikaesi = 0; //折り返しカウンタリセット
    }
  }
  if ($orikaesi != 6) {//日曜終りでなければ空セル発射
    while ($orikaesi < 6) {
      echo "<td>&nbsp;</td>";
      $orikaesi++;
    }
  }
  echo "</tr>";
  echo "</table>";
}

?>