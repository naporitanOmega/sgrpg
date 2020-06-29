<?php
class utility
{
    public static $dsn  = 'mysql:dbname=sgrpg;host=127.0.0.1';  // 接続先を定義
    public static $user = 'senpai';      // MySQLのユーザーID
    public static $pw   = 'indocurry';   // MySQLのパスワード

    /**
    * 実行結果をJSON形式で返却する
    *
    * @param boolean $status
    * @param array   $value
    * @return void
    */
    function sendResponse($status, $value=[]){
         header('Content-type: application/json');
        echo json_encode([
            'status' => $status,
            'result' => $value
         ]);
     }
}