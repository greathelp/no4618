<?php
 require_once $_SERVER["DOCUMENT_ROOT"] . "/ctrl/db.php";
// require_once "chk_login.php";




// $head = "hhhhh";(←プログラムを入れる時に表示される内容の設定)
//Smarty初期化
require_once $_SERVER["DOCUMENT_ROOT"] . '/rt.php';
require_once $_SERVER["DOCUMENT_ROOT"] .$rt. '/vendor/autoload.php';


//記事取得
$conn = DB_Conn();
$topics = getTopics();


$smarty = new Smarty();
$smarty->setTemplateDir( $tpl );
$smarty->setCompileDir( $templates_c );
$smarty->setCacheDir( $cache );

// $smarty->assign("head", $head);(←上記のプログラムを入れる時に表示される内容の設定とセットで設定する。ここに読み込まれてから、ページファイルの方に読み込まれてwebに表示される)

$smarty->assign("device", $device);
$smarty->assign("rt", $rt);

//記事をテンプレートに渡す
$smarty->assign("topics", $topics);

//テンプレート設定
$smarty->display("index.tpl");
//個別ページの名前を↑ここに記入して設定する
//ファイル名は同じ名前の.phpで作成する
