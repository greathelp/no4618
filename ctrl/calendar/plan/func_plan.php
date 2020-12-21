<?php

//時間を分に変換
// tmptime: [23:45]の形式
function changetime($tmptime) {

    global $hour_width;
    $min_width = $hour_width / 60;

    //  開始時間変換（最初から2文字で判別）
    $tmp = substr($tmptime, 0, 2);
    $tmp_hour = (int) $tmp;

    switch ($tmp) {
        case "00";
        case "01";
        case "02";
        case "03";
        case "04";
        case "05";
            //  ０時～５時は２４時～２９時として計算する
            $tmp_hour = $tmp_hour + 24;
    }
    //  分
    $tmp = substr($tmptime, -2, 2);
    $tmp_min = $tmp;

    //  位置計算
    //  ９時がグラフの一番左なので
    $pos = (int) (($tmp_hour - 9) * $hour_width) + ($min_width * $tmp_min);
    $ret = array(
        'hour' => $tmp_hour,
        'min' => $tmp_min,
        'position' => $pos
    );
    return $ret;
}

function getSchedule($val) {
    global $hour_width;
    $min_width = $hour_width / 60;

    //pr($val);

    $tmp_id = sprintf('%05d', $val['id']);

    //  開始時間
    $tmp = changetime($val['start']);
    $tmp_start = new DateTime(date('Y-m-d') . " " . $val['start'] . ":00");

    $start_hour = $tmp['hour'];
    $start_min = $tmp['min'];
    //開始位置
    $tmp_start_pos = $tmp['position'];

    //  終了時間変換
    $tmp = changetime($val['end']);
    $tmp_end = new DateTime(date('Y-m-d') . " " . $val['end'] . ":00");
    if ($tmp_end->format('G') >= 0 && $tmp_end->format('G') <= 6) {
        //0時-6時　を前日の続きで計算する処理
        $tmptmp = $tmp_end->format("Y-m-d H:i:s");//一旦、書式変換
        $tmp_end_tmp = date("Y-m-d H:i:s", strtotime("+1 day $tmptmp"));//日付を次の日に
        $tmp_end = new DateTime($tmp_end_tmp);//Datetimeオブジェクトに変換
    }
    $end_min = $tmp['position'];
    //  何分勤務か 同じさくらで同じバージョンなのになぜかDiffが使えないサーバがあるので計算する 2015.6.23
    $diff = round(($tmp_end->format('U')) - ($tmp_start->format('U'))) / 60; //秒で出るので60で割れば分になる 2015.6.23
    $kinmu_width = $diff; //そのまま使う 2015.6.23
//                    $kinmu_width = $end_min - $start_min;
    //  終了位置
    $tmp_kinmu = (int) ($min_width * $kinmu_width);

    $ret = array(
        'start' => $tmp_start_pos,
        'kinmu' => $tmp_kinmu
    );

    return $ret;
}

?>