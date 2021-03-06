<?php
/**
 * MySQLに接続しデータを追加する
 *
 */

// 以下のコメントを外すと実行時エラーが発生した際にエラー内容が表示される
// ini_set('display_errors', 'On');
// ini_set('error_reporting', E_ALL);

require_once('utility.php');


//-------------------------------------------------
// 初期値
//-------------------------------------------------
define('DEFAULT_LV', 1);
define('DEFAULT_EXP', 1);
define('DEFAULT_MONEY', 3000);

//-------------------------------------------------
// 準備
//-------------------------------------------------
$dsn  = utility::$dsn;    // 接続先を定義
$user = utility::$user;   // MySQLのユーザーID
$pw   = utility::$pw;     // MySQLのパスワード

// 実行したいSQL
$sql1 = 'INSERT INTO User(lv, exp, money) VALUES(:lv, :exp, :money)';
$sql2 = 'SELECT LAST_INSERT_ID() as id';  // AUTO INCREMENTした値を取得する


//-------------------------------------------------
// SQLを実行
//-------------------------------------------------
try{
  $dbh = new PDO($dsn, $user, $pw);   // 接続
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // エラーモード

  //-------------------------------------------------
  // 新規にレコードを作成
  //-------------------------------------------------
  // SQL準備
  $sth = $dbh->prepare($sql1);
  $sth->bindValue(':lv',    DEFAULT_LV,    PDO::PARAM_INT);
  $sth->bindValue(':exp',   DEFAULT_EXP,   PDO::PARAM_INT);
  $sth->bindValue(':money', DEFAULT_MONEY, PDO::PARAM_INT);

  // 実行
  $sth->execute();

  //-------------------------------------------------
  // AUTO INCREMENTした値を取得
  //-------------------------------------------------
  // SQL準備
  $sth = $dbh->prepare($sql2);

  // 実行
  $sth->execute();

  // 実行結果から1レコード取ってくる
  $buff = $sth->fetch(PDO::FETCH_ASSOC);
}
catch( PDOException $e ) {
  utility::sendResponse(false, 'Database error: '.$e->getMessage());  // 本来エラーメッセージはサーバ内のログへ保存する(悪意のある人間にヒントを与えない)
  exit(1);
}

//-------------------------------------------------
// 実行結果を返却
//-------------------------------------------------
// データが0件
if( $buff === false ){
  utility::sendResponse(false, 'Database error: can not fetch LAST_INSERT_ID()');
}
// データを正常に取得
else{
  utility::sendResponse(true, $buff['id']);
}