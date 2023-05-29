<?php
$s_rate = $_GET["s_rate"];
// echo $s_rate;

require_once 'funcs.php';
/** @var PDO $pdo */
$pdo = db_conn();

$sql = "SELECT * FROM my_tabelog WHERE rate = :s_rate;";
// $sql = "SELECT * FROM my_tabelog WHERE rate = \"☆\";";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':s_rate', $s_rate, PDO::PARAM_STR);
$status = $stmt->execute(); //実行が成功したか失敗したか true or false
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
//JSONに値を渡す場合に使う
$json = json_encode($values, JSON_UNESCAPED_UNICODE);

foreach ($values as $v) { ?>
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