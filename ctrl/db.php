<?PHP

//
//データベース等の基本設定
//ファイル名はDBだけど実際はその他大勢記述
//2012.11.20 MGM用に修正
//2013.5.8 想い出用に修正
ini_set("display_errors","on");
date_default_timezone_set('Asia/Tokyo');
$servername = $_SERVER['SERVER_NAME'];

//include_once dirname( __FILE__ ) . '/ChromePhp.php';
//include_once dirname( __FILE__ ) . '/FirePHPCore/fb.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';


if ($servername != "no4618.localhost") {
  //	本番用はこっち
  //	サーバ
  define("DB_SERVER", "mysql7069.xserver.jp");
  //	データベース名
  define("DB_DBNAME", "dental123_calendar1");
  //	ログインユーザー名
  define("DB_USER", "dental123_cal1");
  //	ログインパスワード
  define("DB_PASS", "cal12345");

  define("ROOT_WWW", "/home/omoide7056/www");
} else {
  //	ローカルテスト用はこっち
  define("DB_SERVER", "localhost");
  define("DB_USER", "greathelp");
  define("DB_PASS", "greathelp");
  define("DB_DBNAME", "dental123_calendar1");

  define("ROOT_WWW", "d:/xampp/htdocs/omoide/www");

}

//DB定義
define("TBL_PLAN", "plans");
define("TBL_SCHEDULE", "tbl_calendar");
define("TBL_TOPIC_NEWS", "topics_news");


/*
 * 	こっから接続用関数類
 */

function DB_Conn()
{

  $rslt = false;
  //$pdo = new PDO("mysql:host=localhost; dbname=pdotest","root", "password");
  if (!$rslt = new PDO("mysql:host=" . DB_SERVER . "; dbname=" . DB_DBNAME, DB_USER, DB_PASS, array(
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`"
    )
  )) {
    Disp_Error("MyDB ConnectError", "{$_SERVER['SCRIPT_NAME']}:DB_Connect:12");
  }
  $rslt->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

  return $rslt;
}


//記事取得
function getTopics()
{
  $conn = DB_Conn();
  $sql = "SELECT * FROM " . TBL_TOPIC_NEWS . " WHERE enable = 1 ORDER BY date1 DESC";
  $result = $conn->query($sql);

  $topics = [];
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $topics[] = [
      "date" => date("Y年n月j日", strtotime($row["date1"])),
      "title" => $row["title"],
      "content" => nl2br(base64_decode($row["body"]))
    ];
  }

  return $topics;
}