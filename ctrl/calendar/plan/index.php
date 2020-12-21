<?php
/*
 * プラン一覧
 *
 */
require_once '../../db.php';
require_once 'func_plan.php';
//編集する出勤日を受ける
$today_date = filter_input(INPUT_GET, "date");

//その日の出勤の女の子取得
$datas = getGirlScheDaySinlge($today_date);
$hour_width = 40;
$min_width = $hour_width / 60;
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>当日スケジュール</title>
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/ui-lightness/jquery-ui.css">
        <link rel="stylesheet" href="../../js/jquery.clockpick.1.2.9.css">
        <!--<link rel="stylesheet" href="../../css/ctrl.css">-->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
        <script src="../../js/jquery.clockpick.1.2.9.js"></script>
        <script src="../../js/plan.js"></script>
        <script>
            $(function () {
                $(".clk").clockpick(
                        {
                            starthour: 0,
                            endhour: 24,
                            showminutes: true,
                            minutedivisions: 12,
                            military: true
                        }, reg);
                ;
            });
            //  clockのコールバック
            function reg() {
                this.val(this.val().replace(":00:00", ":00"));
            }
            function go(gid, tmp_date) {

                /*
                 alert("gid:"+gid);
                 alert("date:"+tmp_date);
                 var start_time = $("#st_"+gid).val();
                 var plan = $("#plan_"+gid).val();
                 alert(start_time);
                 alert(plan);
                 */

                $("#gs_" + gid).submit();

                return true;
            }
        </script>
        <style type="text/css">
            body {
                font-size: 13px;
            }
            a {
                text-decoration: none;
            }
            .box{
                display: table;
                height:120px;
                border-bottom: 1px dashed #aaa;
                margin: 3px auto;
            }
            .box_l{
                display: table-cell;
                width: 180px;
            }
            .box_r{
                display: table-cell;
                width:800px;
                margin:0;
                padding:0;
                text-align: left;
                vertical-align: top;
            }
            .submit {
                float:left;
            }
        </style>
    </head>
    <body style="background-color:#000;">
        <div id="container">
            <div id="head">ヘッダ</div>
            <div id="content">
                <h3 style="width:1024px;margin: 0 auto;color:#FFF;"><?php echo date("Y年m月d日の出勤予定一覧", strtotime($today_date)); ?></h3>
                <div style="width:1024px;margin: 0 auto;background-color: #fff;color:#000;"><a href="../../menu.php">戻る</a></div>
                <div align="center" style="background-color:#FFF;width:1024px;margin: 0 auto;">
                    <?php
                    foreach ($datas as $val) {
                        $tmp_id = sprintf('%05d', $val['id']);
                        $plan = getGirlsPlan($val['id'], $today_date);


                        $stext = $val['comment'];
                        ?>
                        <div class="box">
                            <form action="plan_reg.php" name="gs_<?= $tmp_id ?>" id="gs_<?= $tmp_id ?>" method="POST">
                                <div class="box_l">
                                    <div class="photo">
                                        <img src="<?= $val['photo'] ?>" alt="" height="160">
                                    </div>
                                    <?= $val['name'] ?>
                                </div>

                                <div class="box_r">

                                    <canvas id="gid_<?= $tmp_id ?>" width="800" height="40" style="background-color:#FFF;"></canvas>
                                    <div class="hontai" align="left">
                                        <?php
                                        $dat = "return go('{$tmp_id}','{$today_date}');";
                                        ?>

                                        予約開始時間：<input type="text" name="start_time" class="clk" id="st_<?= $tmp_id ?>">
                                        プラン：<input type="text" name="plan" id="plan_<?= $tmp_id ?>" size="5">分
                                        <input type="submit" value="新規予約" onclick="<?= $dat ?>">
                                        <a href="plan_edit.php?gid=<?= $val['id'] ?>&date=<?= $today_date ?>">予約の編集・削除</a>
                                    </div>
                                    <hr style='border-color: #fefefe;'>
                                    コメント※改行は&nbsp;&nbsp;&lt;br&gt;&nbsp;&nbsp;タグでお願い致します。<br>
                                    <input type="text" name="stext" id="stext_<?= $tmp_id ?>" value="<?= $stext ?>" size="80">
                                    <br>
                                    ※コメントに何か入っていると「あと＊分でご案内可能です」の部分に優先して表示されます
                                </div>
                                <input type="hidden" name="gid" id="gid_id_<?= $val['id'] ?>" value="<?= $val['id'] ?>">
                                <input type="hidden" name="date" id="date_id_<?= $today_date ?>" value="<?= $today_date ?>">
                            </form>
                        </div><!--box-->

                        <div style="clear:both;"></div>
                        <?PHP
                        if ($val['start'] != '' && $val['end'] != '') {
                            $schedata = getSchedule($val);
                            //pr($schedata);
                            //出勤時間を引く
                            echo "<script type=\"text/javascript\">\n";
                            echo "$(function(){\n";

                            $width = (40 / 60) * $schedata['kinmu'] * 1.5;
                            echo "view_schedule('gid_" . $tmp_id . "','" . $schedata['start'] . "','" . $width . "','#F8FCB0',0);\n"; //出勤時間を引く
                            $count = 0;
                            foreach ($plan as $val) {
                                $tmp = changetime($val['start']);
                                $start = $tmp['position'];
                                $width = (40 / 60) * $val['plan'];
                                //  塗る色を決定
                                $color = '#FF7F7C';
                                if ($count % 2 == 0) {
                                    $color = '#A9E3FB';
                                }
                                echo "view_schedule('gid_" . $tmp_id . "','" . $start . "','" . $width . "','" . $color . "',1);\n"; //予定を引く
                                $count++;
                            }
                            echo "write_waku('gid_" . $tmp_id . "');\n";
                            echo "});\n";
                            echo "</script>\n";
                        } else {
                            //スケジュール無しを表示
                            echo "<script type=\"text/javascript\">\n";
                            echo "no_schedule('" . $tmp_id . "');\n";
                            echo "</script>\n";
                        }
                    }//foreach
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>