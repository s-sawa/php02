<?php
//1. POSTデータ取得
$name = $_POST["name"];
$r_name = $_POST["r_name"];
$r_url = $_POST["r_url"];
$r_kind = $_POST["r_kind"];
$visit_date = $_POST["visit_date"];
$rate = $_POST["rate"];
$impression = $_POST["impression"];

//2. DB接続します
require_once 'funcs.php';
// $pdoの型指定  $pdoがPDOという型であることを示す。これを書かないとintelephenseでエラー表示となる。動きには問題ない
/** @var PDO $pdo */
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO my_tabelog(name,r_name,r_url,r_kind,visit_date,rate,impression,indate)VALUES(:name, :r_name, :r_url, :r_kind, :visit_date,:rate,:impression, sysdate());";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':r_name', $r_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':r_url', $r_url, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':r_kind', $r_kind, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':visit_date', $visit_date, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':rate', $rate, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':impression', $impression, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行が成功したか失敗したか true or false

//４．データ登録処理後
if ($status == false) {
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  echo $error;
  exit("SQLError:" . $error[2]);
} else {
  //５．select.phpへリダイレクト
  header("Location: index.php");
  exit();
}
