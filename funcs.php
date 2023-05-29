<?php
//共通に使う関数を記述

//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//DBConnection
// 戻り値の型指定。intelephenseでエラー表示されるため。
/**
 * @return PDO
 */
function db_conn()
{
    // echo "yomikom";
    try {
        //Password:MAMP='root',XAMPP=''
        $pdo = new PDO('mysql:dbname=tabelog;charset=utf8;host=localhost', 'root', '');
        return $pdo;
    } catch (PDOException $e) {
        exit('DBConnectError:' . $e->getMessage());
    }
}

/**
 * @return string
 */
function selectAll()
{
    return "SELECT * FROM my_tabelog;";
}