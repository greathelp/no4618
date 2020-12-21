<?php
// require_once $_SERVER["DOCUMENT_ROOT"] . "/common/db_shokuji.php";
// require_once "chk_login.php";




// $head = "hhhhh";(←プログラムを入れる時に表示される内容の設定)
//Smarty初期化
require_once $_SERVER["DOCUMENT_ROOT"] . '/rt.php';
require_once $_SERVER["DOCUMENT_ROOT"] .$rt. '/vendor/autoload.php';

$smarty = new Smarty();
$smarty->setTemplateDir( $tpl );
$smarty->setCompileDir( $templates_c );
$smarty->setCacheDir( $cache );



$smarty->assign("title","矯正に関連する治療内容など");
$smarty->assign("block_title01","「流山おおたかの森歯科・矯正歯科」の詳細");
$smarty->assign("img01","{$rt}/img/kaiyu_top.jpg");
$smarty->assign("block_txt01","当院の診療案内・診療方針・選ばれる理由について説明しています。");
$smarty->assign("kaiyu_link01","/");


$smarty->assign("block_title02","小児歯科");
$smarty->assign("img02","{$rt}/img/kaiyu_child.jpg");
$smarty->assign("block_txt02","矯正は子供のころからのチェックが重要です。小児歯科はこちらから。");
$smarty->assign("kaiyu_link02","/child/");

$smarty->assign("block_title03","子育て中のお母さまへ");
$smarty->assign("img03","{$rt}/img/kaiyu_parenting.jpg");
$smarty->assign("block_txt03","小さなお子さまがいても安心してご来院ください！");
$smarty->assign("kaiyu_link03","/parenting/");

// $smarty->assign("head", $head);(←上記のプログラムを入れる時に表示される内容の設定とセットで設定する。ここに読み込まれてから、ページファイルの方に読み込まれてwebに表示される)
$smarty->assign("device", $device);
$smarty->assign("rt", $rt);

//テンプレート設定
$smarty->display("ortho.tpl");
//個別ページの名前を↑ここに記入して設定する
//ファイル名は同じ名前の.phpで作成する
