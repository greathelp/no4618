<?PHP

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

ini_set("date.timezone", "Asia/Tokyo");
session_start();

if ($_SERVER["SERVER_NAME"] == "adtasukaru.localhost") {

  //ローカルテスト用はこっち
  define("DB_SERVER", "localhost");
  define("DB_USER", "groovy");
  define("DB_PASS", "groovy");
  define("DB_DBNAME", "greathelp_tools");
} else {

  //本番用はこっち

  //サーバ
  define("DB_SERVER", "mysql8084.xserver.jp");
  //データベース名
  define("DB_DBNAME", "adtasukaru_tools");
  //ログインユーザー名
  define("DB_USER", "adtasukaru_tools");
  //ログインパスワード
  define("DB_PASS", "adtskrtools0903");
}


//DB定義
define('TBL_PREF', "t_pref");
define('TBL_USER', "t_user");
define('TBL_TEMPORARY', "t_temporary");
define('TBL_PRICE', "t_price");
define('TBL_HISTORY', "t_history");
define('TBL_ORDER', "t_order");
define('TBL_RECURRING', "t_recurring");
define('TBL_VISITER', "t_visiter");
define('TBL_USER_IMG', "t_user_img");
define('TBL_ITEM', "t_item");

//tools01
define('TBL_PAGES_POPUP', "t_pages_popup");
define('TBL_LOG_POPUP', "t_log_popup");
define('TBL_FORMATS_POPUP', "t_formats_popup");

define('TBL_PAGES_OVERLAY', "t_pages_overlay");
define('TBL_LOG_OVERLAY', "t_log_overlay");
define('TBL_FORMATS_OVERLAY', "t_formats_overlay");

define('TBL_PAGES_OVERLAY2', "t_pages_overlay2");
define('TBL_LOG_OVERLAY2', "t_log_overlay2");
define('TBL_FORMATS_OVERLAY2', "t_formats_overlay2");

define('TBL_PAGES_GADGET', "t_pages_gadget");
define('TBL_LOG_GADGET', "t_log_gadget");
define('TBL_FORMATS_GADGET', "t_formats_gadget");


//ctrl
define('TBL_POST', "t_post");

/*
* 初期処理
*/

//消費税
$global_tax = 10;

//初回割引(◯倍にする。)
$first_time_discount = 0.1;

//著名
$Signature  = "\n\n";
$Signature .= "------------------------------------------------------\n";
$Signature .= "株式会社あいうえお\n";
$Signature .= "メールアドレス: abcdefg@gmail.com\n";
$Signature .= "電話: 00-0000-0000\n";
$Signature .= "FAX: 00-0000-0000\n";
$Signature .= "住所: 〒000-0000 岐阜県岐阜市◯◯◯◯◯◯◯◯◯\n";
$Signature .= "URL: https://abcdefg.com \n";
$Signature .= "------------------------------------------------------";

//CORS対策
$reqHeaders = apache_request_headers();
$allowedOrigin = [
  'http://localhost:8080',
  'https://adtasukaru.com',
  'https://tools.adtasukaru.com',
  'https://lp.adtasukaru.com',
  'https://adtasukaru.com/lp/',
];
if (in_array($reqHeaders['Origin'], $allowedOrigin)) {
  header("Access-Control-Allow-Origin: {$reqHeaders['Origin']}");
}
header("Access-Control-Allow-Credentials:true");
header('content-type: application/json; charset=utf-8');

$isSmartPhone = isSmartPhone();

//DB接続用関数 PDO使用
function DB_Conn()
{

  $rslt = false;
  //$pdo = new PDO("mysql:host=localhost; dbname=pdotest","root", "password");
  if (!$rslt = new PDO(
    "mysql:host=" . DB_SERVER . "; dbname=" . DB_DBNAME . ";charset=utf8",
    DB_USER,
    DB_PASS,
    array( //      PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`"
    )
  )) {
    Disp_Error("MyDB ConnectError", "{$_SERVER['SCRIPT_NAME']}:DB_Connect:12");
  }
  $rslt->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

  return $rslt;
}

//
//スマホ判定
//iPhone Android でtrue
//
/**
 * UA取得　
 * @return string
 */
function getUserAgent()
{
  $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
  return $userAgent;
}

/**
 * スマホかどうか判定
 * if(isSmartPhone()) でtrueだとスマホ判定
 */
function isSmartPhone()
{


  $ua = getuserAgent();

  if (
    stripos($ua, 'iphone') !== false || // iphone
    stripos($ua, 'ipod') !== false || // ipod
    (stripos($ua, 'android') !== false && stripos($ua, 'mobile') !== false) || // android
    (stripos($ua, 'windows') !== false && stripos($ua, 'mobile') !== false) || // windows phone
    (stripos($ua, 'firefox') !== false && stripos($ua, 'mobile') !== false) || // firefox phone
    (stripos($ua, 'bb10') !== false && stripos($ua, 'mobile') !== false) || // blackberry 10
    (stripos($ua, 'blackberry') !== false) // blackberry
  ) {
    $isSmartPhone = true;
  } else {
    $isSmartPhone = false;
  }

  return $isSmartPhone;
}

function createPassword($length)
{
  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
}
//hash化はコチラをご使用ください。
// $hash = password_hash( $password, PASSWORD_DEFAULT );

function getWeekName($week)
{
  switch ($week) {

    case 0:
      return "日";
      break;

    case 1:
      return "月";
      break;

    case 2:
      return "火";
      break;

    case 3:
      return "水";
      break;

    case 4:
      return "木";
      break;

    case 5:
      return "金";
      break;

    case 6:
      return "土";
      break;
  }
}

function getMonthNum($month)
{

  if ($month === "Jan") {
    $month_num = "01";
  } else if ($month === "Feb") {
    $month_num = "02";
  } else if ($month === "Mar") {
    $month_num = "03";
  } else if ($month === "Apr") {
    $month_num = "04";
  } else if ($month === "May") {
    $month_num = "05";
  } else if ($month === "Jun") {
    $month_num = "06";
  } else if ($month === "Jul") {
    $month_num = "07";
  } else if ($month === "Aug") {
    $month_num = "08";
  } else if ($month === "Sep") {
    $month_num = "09";
  } else if ($month === "Oct") {
    $month_num = "10";
  } else if ($month === "Nov") {
    $month_num = "11";
  } else if ($month === "Dec") {
    $month_num = "12";
  }

  return $month_num;
}

function getNumberFromPref($prefecture)
{
  // 都道府県毎に置き換える文字または数字をいれておく
  $table = [
    "北海道" => 1,
    "青森県" => 2,
    "岩手県" => 3,
    "宮城県" => 4,
    "秋田県" => 5,
    "山形県" => 6,
    "福島県" => 7,
    "茨城県" => 8,
    "栃木県" => 9,
    "群馬県" => 10,
    "埼玉県" => 11,
    "千葉県" => 12,
    "東京都" => 13,
    "神奈川県" => 14,
    "新潟県" => 15,
    "富山県" => 16,
    "石川県" => 17,
    "福井県" => 18,
    "山梨県" => 19,
    "長野県" => 20,
    "岐阜県" => 21,
    "静岡県" => 22,
    "愛知県" => 23,
    "三重県" => 24,
    "滋賀県" => 25,
    "京都府" => 26,
    "大阪府" => 27,
    "兵庫県" => 28,
    "奈良県" => 29,
    "和歌山県" => 30,
    "鳥取県" => 31,
    "島根県" => 32,
    "岡山県" => 33,
    "広島県" => 34,
    "山口県" => 35,
    "徳島県" => 36,
    "香川県" => 37,
    "愛媛県" => 38,
    "高知県" => 39,
    "福岡県" => 40,
    "佐賀県" => 41,
    "長崎県" => 42,
    "熊本県" => 43,
    "大分県" => 44,
    "宮崎県" => 45,
    "鹿児島県" => 46,
    "沖縄県" => 47
  ];
  // 変換させる。stringでなくてもhogeとかでも大丈夫。
  $number = str_replace(array_keys($table), array_values($table), $prefecture);

  return $number;
}

function getMaxNumFromHistory($user_id)
{
  $conn = DB_Conn();
  $sql  = "SELECT MAX(num) AS M1 FROM " . TBL_HISTORY . " WHERE user_id = :user_id";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);
  $new_id = $row["M1"] + 1;
  return $new_id;
}

function getAmount($tool_id, $payment, $price)
{
  $conn = DB_Conn();
  $sql  = "SELECT amount FROM " . TBL_PRICE . " WHERE ";
  $sql .= "tool_id = :tool_id AND ";
  $sql .= "payment = :payment AND ";
  $sql .= "price = :price";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":tool_id", $tool_id);
  $rslt->bindValue(":payment", $payment);
  $rslt->bindValue(":price", $price);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);
  return $row["amount"];
}

function getUserData($user_id)
{
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . TBL_USER . " WHERE user_id = :user_id";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function getNewFormatId($user_id, $tool, $site_id)
{
  $conn = DB_Conn();
  if ($tool === "popup") {
    $db_table = TBL_FORMATS_POPUP;
    $db_table2 = TBL_PAGES_POPUP;
  } else if ($tool === "overlay01") {
    $db_table = TBL_FORMATS_OVERLAY;
    $db_table2 = TBL_PAGES_OVERLAY;
  } else if ($tool === "overlay02") {
    $db_table = TBL_FORMATS_OVERLAY2;
    $db_table2 = TBL_PAGES_OVERLAY2;
  } else if ($tool === "gadget") {
    $db_table = TBL_FORMATS_GADGET;
    $db_table2 = TBL_PAGES_GADGET;
  }
  $sql  = "SELECT * FROM " . $db_table2 . " WHERE site_id = :site_id AND user_id = :user_id AND enable = 1";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->bindValue(":site_id", $site_id);
  $rslt->execute();
  if ($rslt->rowCount() == 0) {

    return "NO";
  } else {

    $sql  = "SELECT MAX(format_id) AS M1 FROM " . $db_table . " WHERE site_id = :site_id";
    $rslt = $conn->prepare($sql);
    $rslt->bindValue(":site_id", $site_id);
    $rslt->execute();
    $row = $rslt->fetch(PDO::FETCH_ASSOC);
    $new_id = $row["M1"] + 1;

    return $new_id;
  }
}

//ツール名を取得
function getToolInfo($tool_code)
{
  $tool = [];
  if ($tool_code == "tools" || $tool_code == '0') {
    $tool = [
      "id" => 0,
      "code" => "tools",
      "name" => "GHマーケティングツール"
    ];
  } else if ($tool_code == "popup" || $tool_code == '1') {
    $tool = [
      "id" => 1,
      "code" => "popup",
      "name" => "離脱防止ポップアップ"
    ];
  } else if ($tool_code == "overlay" || $tool_code == '2') {
    $tool = [
      "id" => 2,
      "code" => "overlay",
      "name" => "オーバーレイ広告"
    ];
  } else if ($tool_code == "gadget" || $tool_code == '4') {
    $tool = [
      "id" => 4,
      "code" => "gadget",
      "name" => "ガジェット"
    ];
  }
  return $tool;
}

//ツール一覧+その他情報を取得
function getToolInfo2($user_id)
{
  $tool_id = [1, 2, 4];
  $list = [];
  $data = getUserData($user_id);
  foreach ($tool_id as $val) {
    $tool = getToolInfo($val);
    $tool_stock = $tool["code"] . "_stock";
    $tool_plan = $tool["code"] . "_plan";
    if (
      $val == 1 ||
      $val == 2
    ) {
      $stock = $data[$tool_stock];
      $plan = $data[$tool_plan];
    } else {
      $stock = '';
      $plan = '';
    }
    $list[] = [
      "id" => $tool["id"],
      "code" => $tool["code"],
      "name" => $tool["name"],
      "stock" => $stock,
      "plan" => $plan
    ];
  }

  return $list;
}

//「自動決済」「都度決済」
function getPaymentName($payment_id)
{
  if ($payment_id == 0) {
    $payment_name = "自動決済";
  } else if ($payment_id == 1) {
    $payment_name = "都度決済";
  }
  return $payment_name;
}

//当日の同一人物を判別
function checkVisiterHistory($user_id, $tool_id, $type, $ip, $referer)
{
  $conn = DB_Conn();
  $today = date("Y-m-d 00:00:00");
  $sql  = "SELECT * FROM " . TBL_VISITER . " WHERE user_id = :user_id AND tool_id = :tool_id AND type = :type AND referer = :referer AND ip = :ip AND :start <= visit_datetime AND visit_datetime < :end";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->bindValue(":tool_id", $tool_id);
  $rslt->bindValue(":type", $type);
  $rslt->bindValue(":referer", $referer);
  $rslt->bindValue(":ip", $ip);
  $rslt->bindValue(":start", $today);
  $rslt->bindValue(":end", date("Y-m-d H:i:s", strtotime("$today + 1day")));
  $rslt->execute();
  $rowCount = $rslt->rowCount();
  if ($rowCount == 0) {
    $sql = "INSERT INTO " . TBL_VISITER . " SET ";
    $sql .= "user_id = :user_id, ";
    $sql .= "tool_id = :tool_id, ";
    $sql .= "ip = :ip, ";
    $sql .= "type = :type, ";
    $sql .= "host = :host, ";
    $sql .= "referer = :referer, ";
    $sql .= "visit_datetime = :visit_datetime";
    $rslt = $conn->prepare($sql);
    $rslt->bindValue(":user_id", $user_id);
    $rslt->bindValue(":tool_id", $tool_id);
    $rslt->bindValue(":ip", $ip);
    $rslt->bindValue(":type", $type);
    $rslt->bindValue(":host", gethostbyaddr($ip));
    $rslt->bindValue(":referer", $referer);
    $rslt->bindValue(":visit_datetime", date("Y-m-d H:i:s"));
    $rslt->execute();
  }
  return $rowCount;
}

//
function checkColorCode($color_code)
{
  if (substr($color_code, 0, 1) === "#") {
    $color = $color_code;
  } else {
    $color = "transparent";
  }
  return $color;
}

//パラメーターからページ情報を取得
function getPageInfoByParameter($tool_id, $parameter)
{
  if ($tool_id == 1) {
    $db_tbl = TBL_PAGES_POPUP;
  } else if ($tool_id == 2) {
    $db_tbl = TBL_PAGES_OVERLAY;
  } else if ($tool_id == 3) {
    $db_tbl = TBL_PAGES_OVERLAY2;
  } else if ($tool_id == 4) {
    $db_tbl = TBL_PAGES_GADGET;
  }
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . $db_tbl . " WHERE parameter = :parameter";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":parameter", $parameter);
  $rslt->execute();
  if ($rslt->rowCount() > 0) {
    $row = $rslt->fetch(PDO::FETCH_ASSOC);
  } else {
    $row = 0;
  }
  return $row;
}

//アクセス数+1
function addAmountA($tool_id, $site_id)
{
  if ($tool_id == 1) {
    $db_table = TBL_LOG_POPUP;
  } else if ($tool_id == 2) {
    $db_table = TBL_LOG_OVERLAY;
  } else if ($tool_id == 3) {
    $db_table = TBL_LOG_OVERLAY2;
  } else if ($tool_id == 4) {
    $db_table = TBL_LOG_GADGET;
  }
  $conn = DB_Conn();
  $sql  = "UPDATE " . $db_table . " SET ";
  $sql  .= "amountA = amountA + 1 ";
  $sql  .= "WHERE site_id = :site_id AND record_date =:record_date";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":site_id", $site_id);
  $rslt->bindValue(":record_date", date("Y-m-d"));
  $rslt->execute();
}

// 「/api/tools01/???/regSite.php」用
function regSite($tool, $copy_id, $user_id, $name, $domain)
{
  $conn = DB_Conn();

  //テーブルを定義
  if ($tool == 'popup') {
    $tbl_pages = TBL_PAGES_POPUP;
    $tbl_formats = TBL_FORMATS_POPUP;
  } else if ($tool == 'overlay01') {
    $tbl_pages = TBL_PAGES_OVERLAY;
    $tbl_formats = TBL_FORMATS_OVERLAY;
  } else if ($tool == 'overlay02') {
    $tbl_pages = TBL_PAGES_OVERLAY2;
    $tbl_formats = TBL_FORMATS_OVERLAY2;
  } else if ($tool == 'gadget') {
    $tbl_pages = TBL_PAGES_GADGET;
    $tbl_formats = TBL_FORMATS_GADGET;
  }

  //パラメータの重複をチェック
  $a = 0;
  while ($a < 1) {
    $parameter = createPassword(10);
    $sql  = "SELECT * FROM " . $tbl_pages . " WHERE parameter = :parameter";
    $rslt = $conn->prepare($sql);
    $rslt->bindValue(":parameter", $parameter);
    $rslt->execute();
    if ($rslt->rowCount() === 0) {
      $a++;
    }
  }

  //新規ページIDを発行
  $sql  = "SELECT MAX(site_id) AS M1 FROM " . $tbl_pages;
  $rslt = $conn->prepare($sql);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);
  $new_id = $row["M1"] + 1;

  //コピーするページ情報を取得
  if ($copy_id != 0) {
    $sql  = "SELECT * FROM " . $tbl_pages . " WHERE site_id = :site_id AND user_id = :user_id";
    $rslt = $conn->prepare($sql);
    $rslt->bindValue(":site_id", $copy_id);
    $rslt->bindValue(":user_id", $user_id);
    $rslt->execute();
    $row = $rslt->fetch(PDO::FETCH_ASSOC);
  }

  //ページのテーブルに格納
  $sql = "INSERT INTO " . $tbl_pages . " SET ";
  $sql .= "site_id = :site_id , ";
  $sql .= "user_id = :user_id , ";
  $sql .= "name = :name , ";
  $sql .= "domain = :domain , ";
  $sql .= "parameter = :parameter , ";
  if ($copy_id != 0) {
    $sql .= "display = :display , ";
    $sql .= "jump_page = :jump_page , ";
    $sql .= "memo = :memo , ";
  }
  $sql .= "update_datetime = :update_datetime , ";
  $sql .= "create_datetime = :update_datetime ";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":site_id", $new_id);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->bindValue(":name", $name);
  $rslt->bindValue(":domain", $domain);
  $rslt->bindValue(":parameter", $parameter);
  if ($copy_id != 0) {
    $rslt->bindValue(":display", $row["display"]);
    $rslt->bindValue(":jump_page", $row["jump_page"]);
    $rslt->bindValue(":memo", $row["memo"]);
  }
  $rslt->bindValue(":update_datetime", date("Y-m-d H:i:s"));
  $rslt->execute();

  $items = [];
  if ($copy_id != 0) {
    //ページIDからフォーマットを取得
    $sql  = "SELECT * FROM " . $tbl_formats . " WHERE site_id = :site_id AND user_id = :user_id AND enable = 1";
    $rslt = $conn->prepare($sql);
    $rslt->bindValue(":site_id", $copy_id);
    $rslt->bindValue(":user_id", $user_id);
    $rslt->execute();
    while ($row = $rslt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $row;
    }
  }

  $list = [
    "new_id" => $new_id,
    "items" => $items
  ];

  return $list;
}

// 何らかのツールを使用していた場合に1-OKが返ってくる。
// 何も使用していない場合は0-NG。
function checkPlanAll($user_id)
{
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . TBL_USER . " WHERE user_id = :user_id";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);
  if (
    $row["popup_stock"] == 0 &&
    $row["overlay_stock"] == 0 &&
    $row["gadget_plan"] == 0
  ) {
    $check = [
      "enable" => 0,
      "mess" => "NG"
    ];
  } else {
    $check = [
      "enable" => 1,
      "mess" => "OK"
    ];
  }

  return $check;
}

// ユーザーの登録した商品一覧を取ってくる
function getUserItemList($user_id)
{
  //基本情報を取得
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . TBL_ITEM . " WHERE user_id = :user_id AND enable = 1";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->execute();
  $list = [];
  while ($row = $rslt->fetch(PDO::FETCH_ASSOC)) {
    $list[]  = [
      "id" => $row["item_id"],
      "name" => $row["name"],
      "price" => $row["price"],
      "img" => $row["img"],
    ];
  }

  return $list;
}

// ユーザーの登録した商品情報を取ってくる
function getItemDetail($user_id, $item_id)
{
  //基本情報を取得
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . TBL_ITEM . " WHERE item_id = :item_id AND user_id = :user_id AND enable = 1";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":item_id", $item_id);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);

  return $row;
}

// フォーマット情報を取ってくる
function getFormatInfo($tool_id, $user_id, $site_id, $format_id)
{
  switch ($tool_id) {
    case 1:
      $tbl_formats = TBL_FORMATS_POPUP;
      break;
    case 2:
      $tbl_formats = TBL_FORMATS_OVERLAY;
      break;
    case 4:
      $tbl_formats = TBL_FORMATS_GADGET;
      break;
  }
  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . $tbl_formats . " WHERE site_id = :site_id AND user_id = :user_id AND format_id = :format_id AND enable = 1";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":user_id", $user_id);
  $rslt->bindValue(":site_id", $site_id);
  $rslt->bindValue(":format_id", $format_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);

  return $row;
}

// 県名を取ってくる
function getPrefName($pref_id)
{

  $conn = DB_Conn();
  $sql  = "SELECT * FROM " . TBL_PREF . " WHERE id = :id";
  $rslt = $conn->prepare($sql);
  $rslt->bindValue(":id", $pref_id);
  $rslt->execute();
  $row = $rslt->fetch(PDO::FETCH_ASSOC);

  return $row["name"];
}

//ツールに応じてTBLを取ってくる。
function getTableName($tool)
{
  if ($tool == 1 || $tool == "popup") {
    $tbl = [
      "formats" => TBL_FORMATS_POPUP,
      "log" => TBL_LOG_POPUP,
      "pages" => TBL_PAGES_POPUP,
    ];
  } else if ($tool == 2 || $tool == "overlay01") {
    $tbl = [
      "formats" => TBL_FORMATS_OVERLAY,
      "log" => TBL_LOG_OVERLAY,
      "pages" => TBL_PAGES_OVERLAY,
    ];
  } else if ($tool == 3 || $tool == "overlay02") {
    $tbl = [
      "formats" => TBL_FORMATS_OVERLAY2,
      "log" => TBL_LOG_OVERLAY2,
      "pages" => TBL_PAGES_OVERLAY2,
    ];
  } else if ($tool == 4 || $tool == "gadget") {
    $tbl = [
      "formats" => TBL_FORMATS_GADGET,
      "log" => TBL_LOG_GADGET,
      "pages" => TBL_PAGES_GADGET,
    ];
  }

  return $tbl;
}

//
function getFormatType($tool)
{
  switch ($tool) {

    case 'popup':
      $list = [
        [
          'value' => 1,
          'label' => 'タイプA',
        ],
        [
          'value' => 2,
          'label' => 'タイプB',
        ],
        [
          'value' => 3,
          'label' => '画像のみ',
        ],
      ];
      break;

    case 'overlay01':
      $list = [
        [
          'value' => 1,
          'label' => 'タイプA',
        ],
        [
          'value' => 2,
          'label' => '画像のみ',
        ],
      ];
      break;

    case 'overlay02':
      $list = [
        [
          'value' => 1,
          'label' => 'ランダム',
        ],
        [
          'value' => 2,
          'label' => '上昇',
        ],
        [
          'value' => 3,
          'label' => '減少',
        ],
      ];
      break;

    case 'gadget':
      $list = [
        [
          'value' => 1,
          'label' => 'タイプA',
        ],
        [
          'value' => 2,
          'label' => 'タイプB',
        ],
        [
          'value' => 3,
          'label' => 'タイプC',
        ],
      ];
      break;
  }

  return $list;
}

//
function base64_encode_urlsafe($json_array){
	$json_array = base64_encode($json_array);
	return(str_replace(array('+','=','/'),array('_','-','.'),$json_array));
}