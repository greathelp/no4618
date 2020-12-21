<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/common/db.php";

$conf = array('mode'=>0777,'lineFormat' => '%1$s %3$s %2$s [%6$s] - %4$s','timeFormat'=>'%Y/%m/%d %H:%M:%S');
$log = Log::singleton('file', $_SERVER["DOCUMENT_ROOT"].'/log/common/' . date("Y") . "/" . date("m") . "/" . date("Ymd") . "_settle.txt", '', $conf, PEAR_LOG_DEBUG);
$log->log( "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝　開始　＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝" );
$log->log( "REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] );
$log->log( "gethostbyaddr:" . gethostbyaddr( $_SERVER["REMOTE_ADDR"] ) );

$type = filter_input(INPUT_POST, "type");// 0:新規登録時, 1:2回目以降
if($type == 0) {
  $temp_id = $_SESSION["TEMP_ID"];
  $temp_pass = filter_input(INPUT_POST, "temp_pass");
}else {
  $user_id = $_SESSION["USER_ID"];
}
$tool_id = filter_input(INPUT_POST, "tool_id");
$payment = filter_input(INPUT_POST, "payment");
$plan = filter_input(INPUT_POST, "plan");
$plan2 = substr($plan, 0, -3);
//初回の場合、◯％割引
if($type == 0) {
  $price = floor($plan * $first_time_discount);
}else {
  $price = $plan;
}
$log->log('type: '.$type);
$log->log('tool_id: '.$tool_id);
$log->log('payment: '.$payment);
$log->log('plan: '.$plan);
$log->log('plan2: '.$plan2);
$log->log('price: '.$price);

$conn = DB_Conn();

// new_order_idの最終6桁を取得
$sql  = "SELECT MAX(num) AS M1 FROM ".TBL_ORDER." WHERE ";
$sql .= "times = :times AND ";
$sql .= "tool_id = :tool_id AND ";
$sql .= "payment = :payment AND ";
$sql .= "plan = :plan";
$rslt = $conn->prepare( $sql );
$rslt->bindValue( ":times", $type );
$rslt->bindValue( ":tool_id", $tool_id );
$rslt->bindValue( ":payment", $payment );
$rslt->bindValue( ":plan", $plan );
$rslt->execute();
$row = $rslt->fetch( PDO::FETCH_ASSOC );
$num = $row["M1"] + 1;


// order_idを生成
$new_order_id  = substr("0".$type, -2);// 00（00:初回 or 01:2回目以降）
$new_order_id .= substr("0".$tool_id, -2);// 00（ツールID）
$new_order_id .= substr("0".$payment, -2);// 00（自動決済or都度決済）
$new_order_id .= substr("00".$plan2, -3);// 000（プラン×1000）
$new_order_id .= substr("000000".$num, -7);// 0000000（重複防止）
$log->log('new_order_id: '.$new_order_id);
// 例：0101000050000123（16桁）


// t_orderに格納
$sql  = "INSERT INTO ".TBL_ORDER." SET ";
$sql .= "order_id = :order_id, ";
$sql .= "user_id = :user_id, ";
$sql .= "price = :price, ";
$sql .= "tax = :tax, ";
$sql .= "times = :times, ";
$sql .= "tool_id = :tool_id, ";
$sql .= "payment = :payment, ";
$sql .= "plan = :plan, ";
$sql .= "num = :num, ";
$sql .= "order_datetime = :order_datetime";
$rslt = $conn->prepare( $sql );
$rslt->bindValue( ":order_id", $new_order_id );
if($type == 0) {
  $rslt->bindValue( ":user_id", 0 );
}else {
  $rslt->bindValue( ":user_id", $user_id );
}
$rslt->bindValue( ":price", $price );
$rslt->bindValue( ":tax", $global_tax );
$rslt->bindValue( ":times", $type );
$rslt->bindValue( ":tool_id", $tool_id );
$rslt->bindValue( ":payment", $payment );
$rslt->bindValue( ":plan", $plan );
$rslt->bindValue( ":num", $num );
$rslt->bindValue( ":order_datetime", date("Y-m-d H:i:s") );
$rslt->execute();

// t_temporaryにorder_idを格納
if($type == 0) {
  $sql  = "UPDATE ".TBL_TEMPORARY." SET ";
  $sql .= "order_id = :order_id ";
  $sql .= "WHERE id = :temp_id AND pass = :temp_pass";
  $rslt = $conn->prepare( $sql );
  $rslt->bindValue( ":order_id", $new_order_id );
  $rslt->bindValue( ":temp_id", $temp_id );
  $rslt->bindValue( ":temp_pass", $temp_pass );
  $rslt->execute();
}

$receipt = getReceipt($tool_id, $payment, $plan);

$settleArray = [
  "configid" => 'template01',
  "transaction" => [
    "OrderID" => $new_order_id,
    "Amount" => $price,
    "Tax" => floor($price*($global_tax/100)),
    "ClientField1" => $receipt["tool"],
    "ClientField2" => $receipt["payment"],
    "ClientField3" => $receipt["plan"],
    "PayMethods" => ["credit"]
  ],
  "credit" => [
    "JobCd" => "CAPTURE",
    "Method" => "1",
  ]
];

$settleArray2 = json_encode($settleArray);
$settleArray3 = base64_encode_urlsafe($settleArray2);
$settleArray4 = hash('sha256', $settleArray3."q1zdwepf");
$settleArray5 = $settleArray3.".".$settleArray4;

$URL = 'https://stg.link.mul-pay.jp/v1/plus/tshop00038502/checkout/'.$settleArray5;

$ret = [
  "mess" => "OK",
  "URL" => $URL,
  "OrderID" => $OrderID
];
echo json_encode($ret);

function getReceipt($tool_id, $payment, $plan) {

  //ツール名を取得
  $tool = getToolInfo($tool_id);

  //支払い方法を取得
  if($payment == 0) {
    $payment_name = "定額制";
  }else if($payment == 1){
    $payment_name = "単品購入";
  }

  //プラン名を取得

  $item = [
    "tool" => $tool["name"],
    "payment" => $payment_name,
    "plan" => $plan,
  ];

  return $item;
}