<?php
//変数がセットされている場合に実行させる
if (isset($_GET["order"])) {
  $order = $_GET["order"];
} else {
  //デフォルト
  $order = "DESC";
}
// echo $order;
//1.  DB接続します
require_once 'funcs.php';
// $pdoの型指定  $pdoがPDOという型であることを示す。これを書かないとintelephenseでエラー表示となる。動きには問題ない
/** @var PDO $pdo */
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT * FROM my_tabelog ORDER BY id $order ;";
// $sql = "SELECT * FROM my_tabelog ORDER BY id $order ;";
$stmt = $pdo->prepare($sql);
// $stmt->bindValue(':order', $order, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//３．データ表示
if ($status == false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQLError:" . $error[2]);
}

//全データ取得 object変数として全て(ALL)渡す
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
//JSONに値を渡す場合に使う
$json = json_encode($values, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>フリーアンケート表示</title>
  <link rel="stylesheet" href="css/range.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid flex">
        <div class="navbar-header">
          <a class="navbar-brand" href="input.php">データ登録</a>
        </div>
      </div>
    </nav>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <div>
    <p><a href="./input.php">新しく投稿する</a></p>
    <p><a href="./index.php?order=desc">降順ソート</a></p>
    <p><a href="./index.php?order=asc">昇順ソート</a></p>
    <p><a href="./input.php">検索</a></p>
    <div class="container jumbotron">

      <!-- 1行ずつ左の　$valuesからvに取り出す -->
      <?php foreach ($values as $v) { ?>
        <div>
          <p><a href="u_view.php?id=<?= $v['id']; ?>"><?= h($v["r_name"]) ?></a> <a href="<?= h($v["r_url"]) ?>" <?php if ($v["r_url"] == "") echo "hidden"; ?>>お店のページへ</a></p>
          <p>ジャンル：<?= h($v["r_kind"])  ?></p>
          <p>評価：<?= h($v["rate"])  ?></p>
          <p>訪問:<?= h($v["visit_date"])  ?></p>
          <p>投稿者:<?= h($v["name"])  ?></p>
          <p>投稿日時:<?= h($v["indate"])  ?></p>
          <p>感想：<?= h($v["impression"])  ?></p>
          <img src="./img/deletebtn.svg" alt="" onclick='getId(<?= $v["id"] ?>)' width="25px">
        </div>
        <div>
        </div>
        <hr>
      <?php } ?>
    </div>
  </div>
  <!-- Main[End] -->

  <script>
    //JSON受け取り
    const json = JSON.parse('<?= $json ?>');
    console.log(json);

    function getIndex(id) {
      const index = json.findIndex(ele => ele.id == id);
      return index;
    }

    function getId(id) {
      const i = getIndex(id);
      console.log(i)
      Swal.fire({
        title: `${json[i].r_name}の登録データを削除しますか？`,
        text: "元に戻せません!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'はい',
        cancelButtonText: 'キャンセル',
      }).then((result) => {
        if (result.isConfirmed) {
          deleteData(id);
        } else {
          Swal.fire(
            'キャンセルしました！',
            '',
            'info',
          );
        }
      });
    }

    function deleteData(id) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'http://localhost/task/php02/delete.php?id=' + id, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          Swal.fire(
            '削除しました🫡',
            '',
            'success'
          ).then(() => {
            // 削除後にページをリロード
            location.reload();
          });
        } else {
          Swal.fire(
            'Error',
            'Failed to delete the file.',
            'error'
          );
        }
      };
      xhr.send();
    }

    // function test(id) {
    //   Swal.fire({
    //     title: 'Are you sure?',
    //     text: "You won't be able to revert this!",
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, delete it!'
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       Swal.fire(
    //         'Deleted!',
    //         'Your file has been deleted.',
    //         'success'
    //       )
    //     }
    //   })
    // }
  </script>
</body>

</html>