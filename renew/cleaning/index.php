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



$smarty->assign("title","クリーニング・歯石除去に関連する治療内容など");
$smarty->assign("block_title01","「流山おおたかの森歯科・矯正歯科」の詳細");
$smarty->assign("img01","{$rt}/img/kaiyu_top.jpg");
$smarty->assign("block_txt01","当院の診療案内・診療方針・選ばれる理由について説明しています。");
$smarty->assign("kaiyu_link01","/");


$smarty->assign("block_title02","定期検診");
$smarty->assign("img02","{$rt}/img/kaiyu_prevention.jpg");
$smarty->assign("block_txt02","歯石除去と定期健診は、同時におこなうのが理想的です。定期健診について、詳しくはこちらから。");
$smarty->assign("kaiyu_link02","/prevention/");


// $smarty->assign("head", $head);(←上記のプログラムを入れる時に表示される内容の設定とセットで設定する。ここに読み込まれてから、ページファイルの方に読み込まれてwebに表示される)
$smarty->assign("device", $device);
$smarty->assign("rt", $rt);

//テンプレート設定
$smarty->display("cleaning.tpl");
//個別ページの名前を↑ここに記入して設定する
//ファイル名は同じ名前の.phpで作成する
