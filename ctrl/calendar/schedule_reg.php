<?php

/*
 * スケジュール登録スクリプト
 */

//ログイン済みのチェック
include_once ("../lgchk.php");
require_once '../db.php';

//誰の何年何月か？
$gid = $_POST['gid'];
$year = $_POST['year'];
$month = $_POST['month'];


$gid5 = sprintf( "%05d" , $gid );
//fb::log("gid5:" . $gid5);
//今は何年何月か？
$now_yearmonth = date( "Ym" );
//print("now_yearmonth:" . $now_yearmonth . "<br>\n");
//当日より後の物を処理
if ( $now_yearmonth != ($year . $month) ) {
    //当月ではない
    $start_day = 1;
}
else {
    //当月でも今日は１日かも
    if ( date( "j" ) == 1 ) {
        $start_day = 1;
    }
    else {
        $start_day = date( "j" );
    }
}

//処理年月の最終日を取得
$last_day = date( "t" , mktime( 0 , 0 , 0 , $month , 1 , $year ) );

//DB接続
$conn = DB_Conn();

//まずはその月のその子の情報を消す
$del_start = $year . "-" . $month . "-1";
$del_end = $year . "-" . $month . "-" . $last_day;

$sql = "DELETE FROM " . TBL_SCHEDULE . " WHERE gid = {$gid} AND s_date BETWEEN '{$del_start}' AND '{$del_end}' ";
//fb::log("delsql:" . $sql);
$result = $conn->prepare( $sql );
$result->execute();

//処理月の2ケタ
$tmp_month = sprintf( "%02d" , $month );
//チェックの入ってる日付データを順番に登録する
for ( $i = $start_day; $i <= $last_day; $i++ ) {
    //	データがあるか？
    $tmp_date = $year . "-" . $tmp_month . "-" . sprintf( "%02d" , $i );
    if ( isset( $_POST['chk_' . $tmp_date] ) ) {
        //echo $tmp_date . "::" . $_POST['chk' . $tmp_date] . "<br/>";
        if ( $_POST['chk_' . $tmp_date] == "on" ) {
            $s_tmp = $_POST['start_' . $tmp_date];
            $s_time = trim( $s_tmp ) == "0" ? "" : $s_tmp;
            $e_tmp = $_POST['end_' . $tmp_date];
            $e_time = trim( $e_tmp ) == "0" ? "" : $e_tmp;
            $sql = "INSERT INTO " . TBL_SCHEDULE . " SET gid = :gid , s_date = :sdate , s_start = :s_time , s_end = :e_time , s_text = '' ";
            $result = $conn->prepare( $sql );
            $result->bindValue( "gid" , $gid );
            $result->bindValue( "sdate" , $tmp_date );
            $result->bindValue( "s_time" , $s_time );
            $result->bindValue( "e_time" , $e_time );
            $result->execute();
            ChromePhp::log( "ins_sql" . $sql );
        }
    }
}

//リダイレクト
header( "Location: ./schedule_edit.php?gid=" . $gid );
?>