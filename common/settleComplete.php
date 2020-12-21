<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/common/db.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/gmo/src/com/gmo_pg/client/input/RegisterRecurringCreditInput.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/gmo/src/com/gmo_pg/client/tran/RegisterRecurringCredit.php";

$conf = array('mode'=>0777,'lineFormat' => '%1$s %3$s %2$s [%6$s] - %4$s','timeFormat'=>'%Y/%m/%d %H:%M:%S');
$log = Log::singleton('file', $_SERVER["DOCUMENT_ROOT"].'/log/common/' . date("Y") . "/" . date("m") . "/" . date("Ymd") . "_settleComplete.txt", '', $conf, PEAR_LOG_DEBUG);
$log->log( "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝　開始　＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝" );
$log->log( "REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] );
$log->log( "gethostbyaddr:" . gethostbyaddr( $_SERVER["REMOTE_ADDR"] ) );

$data = [
	"ShopID" => filter_input(INPUT_POST, "ShopID"),
	"JobCd" => filter_input(INPUT_POST, "JobCd"),
	"Amount" => filter_input(INPUT_POST, "Amount"),//☆
	"Tax" => filter_input(INPUT_POST, "Tax"),//☆
	"Currency" => filter_input(INPUT_POST, "Currency"),
	"AccessID" => filter_input(INPUT_POST, "AccessID"),
	"OrderID" => filter_input(INPUT_POST, "OrderID"),//☆
	"Forwarded" => filter_input(INPUT_POST, "Forwarded"),
	"Method" => filter_input(INPUT_POST, "Method"),
	"PayTimes" => filter_input(INPUT_POST, "PayTimes"),
	"Approve" => filter_input(INPUT_POST, "Approve"),//☆
	"TranID" => filter_input(INPUT_POST, "TranID"),
	"TranDate" => filter_input(INPUT_POST, "TranDate"),
	"ErrCode" => filter_input(INPUT_POST, "ErrCode"),
	"ErrInfo" => filter_input(INPUT_POST, "ErrInfo"),
	"PayType" => filter_input(INPUT_POST, "PayType")
];
$log->log( "========== data ==========" );
$log->log( $data );

// 直で来た時にログイン画面に飛ばす
if($data["JobCd"] !== "CAPTURE") {
	header( "Location: https://tools.adtasukaru.com/login/" );
	exit;
}

$order_id = $data["OrderID"];
$times = intVal(substr($order_id, 0, 2));// 0:初回、1:2回目以降
$tool_id = intVal(substr($order_id, 2, 2));// 
$payment = intVal(substr($order_id, 4, 2));// 0:自動決済、1:都度決済
$plan = intVal(substr($order_id, 6, 3)."000");
$num = intVal(substr($order_id, 9, 7));
$log->log( "times: ".$times );
$log->log( "tool_id: ".$tool_id );
$log->log( "payment: ".$payment );
$log->log( "plan: ".$plan );
$log->log( "num: ".$num );

$conn = DB_Conn();
if($times == 0) {// 初回決済
	$temp_id = $_SESSION["TEMP_ID"];
	unset($_SESSION["TEMP_ID"]);

	// t_temporaryから情報を取得
	$sql  = "SELECT * FROM " . TBL_TEMPORARY . " WHERE order_id = :order_id";
	$rslt = $conn->prepare( $sql );
	$rslt->bindValue( ":order_id", $data["OrderID"] );
	$rslt->execute();
	$row0 = $rslt->fetch( PDO::FETCH_ASSOC );
	$log->log( "===== row0 =====" );
	$log->log( $row0 );
	
	// t_userの新規IDを取得
	$sql  = "SELECT MAX(user_id) AS M1 FROM ".TBL_USER;
	$rslt = $conn->prepare( $sql );
	$rslt->execute();
	$row00 = $rslt->fetch( PDO::FETCH_ASSOC );
	$new_id = $row00["M1"] + 1;
	$log->log( "new_id: ".$new_id );
	
	$password = createPassword(10);
	$hash = password_hash( $password, PASSWORD_DEFAULT );

	// t_userに移植
	$sql  = "INSERT INTO ".TBL_USER." SET ";
	$sql .= "user_id = :user_id, ";
	$sql .= "name01 = :name01, ";
	$sql .= "name02 = :name02, ";
	$sql .= "reading01 = :reading01, ";
	$sql .= "reading02 = :reading02, ";
	$sql .= "company_name = :company_name, ";
	$sql .= "company_reading = :company_reading, ";
	$sql .= "email = :email, ";
	$sql .= "password = :password, ";
	$sql .= "update_datetime = :create_datetime, ";
	$sql .= "create_datetime = :create_datetime";
	$rslt = $conn->prepare( $sql );
	$rslt->bindValue( ":user_id", $new_id );
	$rslt->bindValue( ":name01", $row0["name01"] );
	$rslt->bindValue( ":name02", $row0["name02"] );
	$rslt->bindValue( ":reading01", $row0["reading01"] );
	$rslt->bindValue( ":reading02", $row0["reading02"] );
	$rslt->bindValue( ":company_name", $row0["company_name"] );
	$rslt->bindValue( ":company_reading", $row0["company_reading"] );
	$rslt->bindValue( ":email", $row0["email"] );
	$rslt->bindValue( ":password", $hash );
	$rslt->bindValue( ":create_datetime", date("Y-m-d H:i:s") );
	$rslt->execute();

	$user_id = $new_id;
	$email = $row0["email"];
	
}else {// 2回目以降

	$sql  = "SELECT * FROM ".TBL_ORDER." WHERE order_id = :order_id";
	$rslt = $conn->prepare( $sql );
	$rslt->bindValue( ":order_id", $data["OrderID"] );
	$rslt->execute();
	$row = $rslt->fetch( PDO::FETCH_ASSOC );
	$user_id = $row["user_id"];

	$user_data = getUserData($user_id);
	$email = $user_data["email"];
}

//自動決済の場合、自動売上処理を付与
if($payment == 0) {

	//新規recurring_idを取得
	$sql  = "SELECT MAX(times) AS M1 FROM ".TBL_RECURRING." WHERE ";
	$sql .= "tool_id = :tool_id AND ";
	$sql .= "user_id = :user_id";
	$rslt = $conn->prepare( $sql );
	$rslt->bindValue( ":tool_id", $tool_id );
	$rslt->bindValue( ":user_id", $user_id );
	$rslt->execute();
	$row = $rslt->fetch(PDO::FETCH_ASSOC);
	$times_r = $row["M1"]+1;
	$tool_id_seed = substr(("0".$tool_id), -2);
	$user_id_seed = substr(("00000".$user_id), -6);
	$times_seed = substr(("00".$times_r), -3);
	$recurring_id = $tool_id_seed.$user_id_seed.$times_seed;

	//自動売上を付与
	$input = new RegisterRecurringCreditInput();
	$input->setShopId( "tshop00038502" );
	$input->setShopPass( "q1zdwepf" );
	$input->setRecurringID( $recurring_id );
	$input->setAmount( $plan );
	$input->setTax( floor($plan*$global_tax/100) );
	$input->setChargeDay( intVal(date("j")) );
	$input->setRegistType( 3 );
	$input->setSrcOrderID( $data["OrderID"] );
	$exe = new RegisterRecurringCredit();
	$output = $exe->exec( $input );
	$log->log("＝＝＝＝＝ output ＝＝＝＝＝");
	$log->log($output);

	//t_recurringに新規追加
	$sql = "INSERT INTO ".TBL_RECURRING." SET ";
	$sql .= "recurring_id = :recurring_id, ";
	$sql .= "tool_id = :tool_id, ";
	$sql .= "user_id = :user_id, ";
	$sql .= "times = :times, ";
	$sql .= "create_datetime = :create_datetime";
	$rslt = $conn->prepare($sql);
	$rslt->bindValue(":recurring_id", $recurring_id);
	$rslt->bindValue(":tool_id", $tool_id);
	$rslt->bindValue(":user_id", $user_id);
	$rslt->bindValue(":times", $times_r);
	$rslt->bindValue(":create_datetime", date("Y-m-d H:i:s"));
	$rslt->execute();

}

// t_orderのenable = 1
$sql  = "UPDATE ".TBL_ORDER." SET ";
$sql .= "user_id = :user_id, ";
$sql .= "enable = 1 ";
$sql .= "WHERE order_id = :order_id";
$rslt = $conn->prepare( $sql );
$rslt->bindValue( ":user_id", $user_id );
$rslt->bindValue( ":order_id", $order_id );
$rslt->execute();

//付与回数を調べる
$sql  = "SELECT * FROM ".TBL_PRICE." WHERE tool_id = :tool_id AND payment = :payment AND price = :price";
$rslt = $conn->prepare( $sql );
$rslt->bindValue( ":tool_id", $tool_id );
$rslt->bindValue( ":payment", $payment );
$rslt->bindValue( ":price", $plan );
$rslt->execute();
$row = $rslt->fetch( PDO::FETCH_ASSOC );
$log->log("amount: ".$row["amount"]);

//付与
$tool = getToolInfo($tool_id);
$tool_name = $tool["code"];
$sql  = "UPDATE ".TBL_USER." SET ";
if(
	$tool_id == 1 ||
	$tool_id == 2
) {
	$sql .= "{$tool_name}_stock = {$tool_name}_stock+{$row["amount"]}";
	if($payment == 0) {
		$sql .= ", {$tool_name}_plan = :plan";
	}
}else if($tool_id == 4) {
	if($payment == 0) {
		$sql .= "{$tool_name}_plan = :plan";
	}
}
$sql .= " WHERE user_id = :user_id";
$rslt = $conn->prepare( $sql );
if($payment == 0) {
	$rslt->bindValue( ":plan", $plan );
}
$rslt->bindValue( ":user_id", $user_id );
$rslt->execute();


//PHPMailer読み込み
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php';
$log->log("PHPMailer読み込み");

if($times == 0) {

	//メール送信（登録完了＆ログイン情報のお知らせ）
	$post_body  = "グレイトヘルプツールズへの登録、ありがとうございます。\n";
	$post_body .= "お客様のログイン情報は以下の通りです。\n\n";
	$post_body .= "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
	$post_body .= "ログインID: ".$email."\n";
	$post_body .= "ログインPW: ".$password."\n";
	$post_body .= "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$post_body .= "ログインはこちらから→ https://tools.greathelp-tools.com/login/ \n\n";
	$post_body .= $Signature;
	$log->log($post_body);

	$mail_address = $email;
	//$mail_address = "sebon77@gmail.com";

	//メールの設定
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = "sv8835.xserver.jp";
	$mail->SMTPAuth = true;
	$mail->Username = 'info@adtasukaru.com';
	$mail->Password = 'ce7EXga9PaAHrxq';
	$mail->SMTPSecure = 'tls';
	$mail->CharSet = 'utf-8';
	$mail->Port = 587;
	$mail->isHTML(false);
	//送信元・送り先
	$mail->addAddress($mail_address);//送り先
	$mail->setFrom('info@adtasukaru.com', 'アドタスカル WEBシステム');
	$mail->Subject = '新規会員登録完了のお知らせ';
	$mail->Body = $post_body;
	$mail->send();

}

//メール送信（決済完了のお知らせ）
$post_body  = "決済完了しました。\n";
$post_body .= "plan: {$plan} \n";
$post_body .= "payment: {$payment}";
$post_body .= $Signature;
$log->log($post_body);

$mail_address = $email;
//$mail_address = "sebon77@gmail.com";

//メールの設定
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = "sv8835.xserver.jp";
$mail->SMTPAuth = true;
$mail->Username = 'info@adtasukaru.com';
$mail->Password = 'ce7EXga9PaAHrxq';
$mail->SMTPSecure = 'tls';
$mail->CharSet = 'utf-8';
$mail->Port = 587;
$mail->isHTML(false);
//送信元・送り先
$mail->addAddress($mail_address);//送り先
$mail->setFrom('info@adtasukaru.com', 'アドタスカル WEBシステム');
$mail->Subject = '決済完了のお知らせ';
$mail->Body = $post_body;
$mail->send();