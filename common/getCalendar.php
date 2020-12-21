<?php

require_once '../ctrl/db.php';

$year  = filter_input(INPUT_GET, "year");
$month = filter_input(INPUT_GET, "month");

//今の翌月が何年何月か？
$this_month = date("Y,m", strtotime('+1 month', strtotime(date("Y") . "-" . date("m") . "-1")));

//前月を取得
//ChromePhp::log(strtotime($year . "-" . $month . "-1"));
$prev = date("Y,m", strtotime('-1 month', strtotime($year . "-" . $month . "-1")));
//ChromePhp::log($prev);
//翌月
$next = date("Y,m", strtotime('+1 month', strtotime($year . "-" . $month . "-1")));
//ChromePhp::log($next);
// 月末日を取得
$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

$calendar = array();
$j        = 0;

//記事を検索しておく
$conn = DB_Conn();
//投稿がある日を調べる
$start_day = $year . "-" . $month . "-01";
$end_day   = $year . "-" . $month . "-" . $last_day;
$days      = [];

//その月の登録済み休日を調べる
$start_day = $year . "-" . $month . "-01";
$end_day   = $year . "-" . $month . "-" . $last_day;
$sql       = "select * from " . TBL_SCHEDULE . " where ";
$sql       .= "cal_date BETWEEN '" . $start_day . "' ";
$sql       .= "and '" . $end_day . "' ";
$sql       .= "order by cal_date";
//    ChromePhp::log($sql);
$rslt        = $conn->query($sql);
$days_closed = [];
while ($row = $rslt->fetch(PDO::FETCH_ASSOC)) {
    $days_closed[ $row['cal_date'] ] = $row['status'];
}

//var_dump($days_closed);


// 月末日までループ
for ($day = 1; $day < $last_day + 1; $day ++) {

  // 曜日を取得
    $week = date('w', mktime(0, 0, 0, $month, $day, $year));

    // echo $day."日は".$week."<br>";
    // 1日の場合
    if ($day == 1) {
        //1日が月曜日では無い場合は開始曜日までの日数分を埋める
        if ($week != 1) {
            // echo "1日は月曜じゃない<br>";
            //日曜日を右端とするので7として処理
            if ($week == 0) {
                $week = 7;
            }
            // 1日目の曜日までをループ
            for ($s = 1; $s <= ($week - 1); $s ++) {
                // 前半に空文字をセット
                $calendar[ $j ]['day'] = '';
                $j ++;
            }
        }
    }

    // 配列に日付をセット
    $calendar[ $j ]['day'] = $day;
    $j ++;

    // 月末日の場合
    // echo "曜日：".$week."<br>";
    if (($day == $last_day) && ( $week != 0 )) {
        // echo "\$day=".$day."&last_day=".$last_day." ループイン<br>";
        // 月末日から残りをループ
        for ($e = 1; $e <= 7 - $week; $e ++) {
            // 後半に空文字をセット
            $calendar[ $j ]['day'] = '';
            $j ++;
        }
    }
}
// echo "<pre>";
// var_dump($calendar);
// echo "</pre>";
/*
 * HTML作成
 */
//翌月以降は表示させないためのチェック
$html = "";

//今の実年月
$year_month_now = date("Ym");

//今表示されている年月
$year_month = $year . sprintf("%02d", $month);

if ($year_month_now != $year_month) {
    //違うのなら翌月が表示されている
    //と言う事は前の月に戻れるけど、翌月は表示してはダメ
    $html =
    "<button onclick=\"getCal({$prev});return false;\">&lt;&lt;</button>" .
    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$year}年{$month}月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
    "<button style='display: none;' onclick=\"getCal({$next});return false;\">&gt;&gt;</button>";
} else {
    //今月が表示されている
    //と言う事は翌月には行けるけど先月は表示してはダメ
    $html =
    "<button style='display: none;' onclick=\"getCal({$prev});return false;\">&lt;&lt;</button>" .
    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$year}年{$month}月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
    "<button onclick=\"getCal({$next});return false;\">&gt;&gt;</button>";
}

$html .=
  "<div class=\"calender_wrap\">" .
  "<table class=\"calender\" width=\"100%\">" .
  "<tr>" .
  "<th align=\"center\">月</th>" .
  "<th align=\"center\">火</th>" .
  "<th align=\"center\">水</th>" .
  "<th align=\"center\">木</th>" .
  "<th align=\"center\">金</th>" .
  "<th class='saturday' align=\"center\">土</th>" .
  "<th class='sunday' align=\"center\">日</th>" .
  "</tr>" .
  "<tr>";

$total_cnt = 0;


//今回は1が月曜日
//1:Mon
//2:Tue
//3:Wed
//4:Thur
//5:Fri
//6:Sat
//7:Sun
//として月曜始まりの日曜日で折り返しとする
$youbi = 0;
foreach ($calendar as $key => $value):

  $youbi ++;

  if ($youbi == 1) {
      $html .= "<tr>";
  }


  if (gettype($value["day"]) != "string") {
      //日付作成
      $check_date = $year . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $value['day']);
      //その日は休日か？
      if (array_key_exists($check_date, $days_closed)) {
          switch ($days_closed[ $check_date ]) {
            case "1"://休診日
              $html .= "<td class=\"close\" align=\"center\">";
              break;
            case "2"://午後短縮
              $html .= "<td class=\"afternoon\" align=\"center\">";
              break;
            case "3"://矯正診療・無料相談日
              $html .= "<td class=\"counseling\" align=\"center\">";
              break;
          }

          $html .= $value['day'];

          $html .= "</td>";
      // } elseif ($total_cnt != 0) {
      } else {
          //営業日
          $html .= "<td align=\"center\">";

          $html .= $value['day'];

          $html .= "</td>";
      }
  } else {
      // echo $value["day"]."はstring<br>";
      $html .= "<td align=\"center\">&nbsp;</td>";
  }//有効な日付かどうかのチェック


  if ($youbi == 7):
    $html  .= "</tr>";
    $youbi = 0;
  endif;

  $total_cnt ++;

endforeach;
$html .= "</tr>" .
         "</table>";


$html .= "</div><!--/calendar-wrap-->";

$html .= "<div class=\"calendar_txt\">
      <p>
        <span style=\"color:red;\">赤字</span>・・・休診日<br>
        <span style=\"color:blue;\">青字</span>・・・午後は14：30〜18：30<br>
        <span style=\"color:green;\">緑字</span>・・・矯正診療・無料相談日(要予約)<br>
        <span style=\"font-weight:bold; line-hight:1.7em; font-size:16px; margin-top:10px;\">※矯正の無料相談日とは</span><br>
        矯正のための口内チェック、矯正に関するご相談、シミュレーションを無料でおこなっております。
        </p>
    </div>";
echo $html;

exit;

/*
 <div class="calender_cover">
              <!-- <div class="calender_title">
                <p>6月カレンダー</p>
              </div> -->
              <button>&lt;&lt;</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2019年6月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button>&gt;&gt;</button>
              <div class="calender_wrap">
                <table class="calender">
                  <tbody>
                    <!-- <tr>
                    <td align="center" colspan="7">
                  </td>
                </tr> -->
                <tr>
                  <th align="center">日</th>
                  <th align="center">月</th>
                  <th align="center">火</th>
                  <th align="center">水</th>
                  <th align="center">木</th>
                  <th align="center">金</th>
                  <th align="center">土</th>
                </tr>
                <tr>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td class="open" align="center">1</td>
                </tr>
                <tr>
                  <td class="afternoon" align="center">2</td>
                  <td class="open" align="center">3</td>
                  <td class="open" align="center">4</td>
                  <td class="open" align="center">5</td>
                  <td class="close" align="center">6</td>
                  <td class="open" align="center">7</td>
                  <td class="open" align="center">8</td>
                </tr>
                <tr>
                  <td class="open" align="center">9</td>
                  <td class="open" align="center">10</td>
                  <td class="open" align="center">11</td>
                  <td class="open" align="center">12</td>
                  <td class="close" align="center">13</td>
                  <td class="open" align="center">14</td>
                  <td class="open" align="center">15</td>
                </tr>
                <tr>
                  <td class="open" align="center">16</td>
                  <td class="open" align="center">17</td>
                  <td class="open" align="center">18</td>
                  <td class="open" align="center">19</td>
                  <td class="close" align="center">20</td>
                  <td class="open" align="center">21</td>
                  <td class="open" align="center">22</td>
                </tr>
                <tr>
                  <td class="counseling" align="center">23</td>
                  <td class="open" align="center">24</td>
                  <td class="open" align="center">25</td>
                  <td class="open" align="center">26</td>
                  <td class="close" align="center">27</td>
                  <td class="open" align="center">28</td>
                  <td class="open" align="center">29</td>
                </tr>
                <tr>
                  <td class="open" align="center">30</td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                  <td align="center"></td>
                </tr>
              </tbody>
            </table>
 */
