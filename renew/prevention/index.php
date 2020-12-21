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



$smarty->assign("title","定期検診に関連する治療内容など");
$smarty->assign("block_title01","「流山おおたかの森歯科・矯正歯科」の詳細");
$smarty->assign("img01","{$rt}/img/kaiyu_top.jpg");
$smarty->assign("block_txt01","当院の診療案内・診療方針・選ばれる理由について説明しています。");
$smarty->assign("kaiyu_link01","/");


$smarty->assign("block_title02","虫歯治療");
$smarty->assign("img02","{$rt}/img/kaiyu_general.jpg");
$smarty->assign("block_txt02","もし虫歯になってしまったら？虫歯治療についての詳細はこちらから。");
$smarty->assign("kaiyu_link02","/general/");


$smarty->assign("block_title03","歯周病");
$smarty->assign("img03","{$rt}/img/kaiyu_perio.jpg");
$smarty->assign("block_txt03","実はこわい歯周病。歯周病の症状や、治療法についての確認はこちらから。");
$smarty->assign("kaiyu_link03","/perio/");


// $smarty->assign("head", $head);(←上記のプログラムを入れる時に表示される内容の設定とセットで設定する。ここに読み込まれてから、ページファイルの方に読み込まれてwebに表示される)
$smarty->assign("device", $device);
$smarty->assign("rt", $rt);

//テンプレート設定
$smarty->display("prevention.tpl");
//個別ページの名前を↑ここに記入して設定する
//ファイル名は同じ名前の.phpで作成する
