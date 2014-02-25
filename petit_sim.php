<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>簡易イベントシミュレータ</title></head>
<body>

<h1>簡易イベントシミュレータ</h1>
<hr/>
[<a href="./">迷宮競技会イベント一覧に戻る</a>]
<hr/>
<a name="top"/>
<?php
require_once "EventParser.php";
require_once "JudgementTable.php";

$bg_cls = array(
  '大' => '#33ffff',
  '勝' => '#99ff99',
  '引' => '#ffff99',
  '負' => '#ffcc66',
  '惨' => '#ff99cc',
);

$ev_dir = './events/';
$ev_text_prefix = 'ev_text_';

// イベント文字列（初期値は空）
$event_text ="";

// GET引数でイベントIDを指定された場合そのファイルを読み込んでevent_textにセットする
if (isset($_GET['evid'])) {
  $evid = $_GET['evid'];
  // .と/の除去
  $chars = array( '.', '/' );
  $evid = str_replace($chars, "", $evid);

  $ev_file = $ev_dir . $ev_text_prefix . $evid . ".txt";
  // print $ev_file . "<br/>";
  if (file_exists($ev_file)) {
    $event_text = file_get_contents ($ev_file);
  } else {
    // ファイルがない場合はそのイベントIDのデータは存在しない旨表示
    print "<p>イベントID：" . $evid . "のデータは登録されていません。<br/>";
    print "お手数をおかけしますが、手動で入力お願いします。</p>";
  }
}

// POSTで
if (isset($_POST['event_text'])) {
  $event_text = $_POST['event_text'];
  // HTMLエスケープくらいはしておく
  $event_text = htmlspecialchars($event_text);
}

if (!empty($event_text)) {
  $ep = new EventParser();
  $ep->parse($event_text);

  $dices = $ep->get_dices();
  $rds = $ep->get_rds();
  $rd_keys = array_keys($rds);

  print "<p><table border=\"1\" cellpadding=\"2\" cellspacing=\"0\">";
  print "<tr align=\"center\">";
  print "<th>戦力比</th>";
  foreach ($rd_keys as $key) {
    print "<th>" . $key . "</th>";
  }
  foreach ($dices as $dice) {
    print "<th>" . $dice . "</th>";
  }
  print "</tr>";

  for ($i=6;$i>-1;$i--) {
    print "<tr align=\"center\">";
    print "<td>$i</td>";
    foreach ($rd_keys as $key) {
      print "<td>" . $rds[$key]->get_power()*$i . "</td>";
    }
    foreach ($dices as $dice) {
      $r = JudgementTable::judge($i,$dice);
      print "<td bgcolor=\"$bg_cls[$r]\">";
      print $r;
      //print "(" . $i . ", " . $dice . ")";
      print "</td>";
    }
    print "</tr>";
  }

  foreach ($rd_keys as $key1) {
    print "<tr align=\"center\">";
    print "<td>必要勝利数</td>";
    foreach ($rd_keys as $key2) {
      print "<td>";
      print $key1==$key2 ? $rds[$key1]->get_snum() : "&nbsp;";
      print "</td>";
    }
    foreach ($dices as $dice) {
      print "<td>" . "&nbsp;" . "</td>";
    }
    print "</tr>";
  }
  print "</table></p>";

}

?>
<p>
<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
<input type="submit" name="submit" value="解析する">
<br><br>
<textarea name="event_text" rows="16" cols="80">
<?php print $event_text; ?>
</textarea>
</form>
</p>

<hr/>
[<a href="#top">先頭に戻る</a>]
[<a href="./">迷宮競技会イベント一覧に戻る</a>]
<hr/>
</body></html>
