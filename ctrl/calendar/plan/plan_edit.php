<?php
/*
 * 指定日の指定の女の子の予約を編集
 */
include_once '../../db.php';

$gid = filter_input( INPUT_GET , "gid" );
$date = filter_input( INPUT_GET , "date" );


$sql = "select * from " . TBL_PLAN . " where gid = " . $gid . " and ";
$sql .= " date = '" . $date . "' ";

$conn = DB_Conn();
$rslt = $conn->query( $sql );
$rowcount = 0;
$gdat = GetGirlData( $gid );
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>プラン修正・削除</title>
        <style>
            td {
                background-color:#fff;
            }
        </style>
    </head>
    <body>
        <?php
        echo "<center>" . $gdat["name"] . "の" . date( "Y年m月d日" , strtotime( $date ) ) . "の予約状況</center>";
        echo "<center><a href=\"index.php?date={$date}\">戻る</a></center>";
        echo "<table width=\"640\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#bbb;\">";
        echo "<tr>";
        echo "<td>";
        echo "管理ID";
        echo "</td>";
        echo "<td>";
        echo "開始時間";
        echo "</td>";
        echo "<td>";
        echo "プラン（分）";
        echo "</td>";
        echo "<td>";
        echo "削除";
        echo "</td>";
        echo "</tr>";
        while ( $row = $rslt->fetch( PDO::FETCH_ASSOC ) ) {

            echo "<tr>";
            echo "<td>";
            echo $row['id'];
            echo "</td>";
            echo "<td>";
            echo $row['start_time'];
            echo "</td>";
            echo "<td>";
            echo $row['plan'];
            echo "</td>";
            echo "<td>";
            echo "<a href=\"del.php?id={$row["id"]}&date={$date}&gid={$gid}\">削除</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </body>
</html>