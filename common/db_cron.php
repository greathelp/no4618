<?PHP
//データベース等の基本設定
//DBとなっているが設定ファイル

//composer

//cron専用

ini_set("date.timezone", "Asia/Tokyo");

//サーバ
define("DB_SERVER", "mysql8084.xserver.jp");
//データベース名
define("DB_DBNAME", "adtasukaru_tools");
//ログインユーザー名
define("DB_USER", "adtasukaru_tools");
//ログインパスワード
define("DB_PASS", "adtskrtools0903");


//DB定義
define('TBL_PREF', "t_pref");
define('TBL_USER', "t_user");
define('TBL_TEMPORARY', "t_temporary");
define('TBL_PRICE', "t_price");
define('TBL_HISTORY', "t_history");
define('TBL_ORDER', "t_order");
define('TBL_RECURRING', "t_recurring");


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
