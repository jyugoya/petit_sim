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
require_once "PCAction.php";

$bg_cls = array(
  '大' => '#33ffff',
  '勝' => '#99ff99',
  '引' => '#ffff99',
  '負' => '#ffcc66',
  '惨' => '#ff99cc',
);

$ev_dir = './events/';
$ev_text_prefix = 'ev_text_';
$ac_text_prefix = 'ac_text_'; // 行動サンプルデータ
$def_ev_file = $ev_dir . $ev_text_prefix . "DEFAULT.txt";

// イベント文字列（初期値は空）
$event_text ="";
// 行動文字列（初期値は汎用）
$action_text = "ＰＣ名【行動名:ランク:任意のタグ:戦力：リスク】";
// 行動結果列（初期値は空）
$action_output = "";

// GET引数でイベントIDを指定された場合そのファイルを読み込んでevent_textにセットする
if (isset($_GET['evid'])) {
  $evid = $_GET['evid'];
  // .と/の除去
  $chars = array( '.', '/' );
  $evid = str_replace($chars, "", $evid);

  $ev_file = $ev_dir . $ev_text_prefix . $evid . ".txt";
  $ac_file = $ev_dir . $ac_text_prefix . $evid . ".txt";
  // print $ev_file . "<br/>";
  if (file_exists($ev_file)) {
    $event_text = file_get_contents ($ev_file);
  } else {
    // ファイルがない場合はそのイベントIDのデータは存在しない旨表示
    print "<p>イベントID：" . $evid . "のデータは登録されていません。<br/>";
    print "お手数をおかけしますが、手動で入力お願いします。";
  }

  if (file_exists($ac_file)) {
    $action_text = file_get_contents ($ac_file);
  }
}

if (empty($event_text) && file_exists($def_ev_file)) {
   print "デフォルトの値を随時書き換えてください。";
   $event_text = file_get_contents ($def_ev_file);
}
print "</p>";

// POSTで来た時の処理
if (isset($_POST['event_text'])) {
  $event_text = $_POST['event_text'];
  // HTMLエスケープくらいはしておく
  $event_text = htmlspecialchars($event_text);
}

if (isset($_POST['action_text'])) {
  $action_text = $_POST['action_text'];
  // HTMLエスケープくらいはしておく
  $action_text = htmlspecialchars($action_text);
}

if (!empty($event_text)) {
  $ep = new EventParser();
  $ep->parse($event_text);

  $dices = $ep->get_dices();
  $rds = $ep->get_rds();
  $rd_keys = array_keys($rds);
  $results = $ep->get_results();

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

  if(!empty($action_text)) {
    mb_regex_encoding("UTF-8");
    $lines = mb_split('\n', $action_text);
    $actions = array();
    foreach ($lines as $line) {
      preg_match('/([^【】]*)【([^：:]*)[：:]([^：:]*)[：:]([^：:]*)[：:]([^：:]*)[：:]([^：:]*)】/u', $line, $matches);
      if ($matches) {
        $actions[] = new PCAction($matches[1],$matches[2],$matches[3],$matches[4],$matches[5],$matches[6]);
      }
    }
    //print_r($actions);

    foreach ($actions as $action) {
      foreach ($rd_keys as $key1) {
        if ($key1 == $action->get_tag()) {
          print "<tr align=\"center\">";
          print "<td>" . $action->get_owner() . "</td>";
          $ratio = (int) ($action->get_power() / $rds[$key1]->get_power());
          $action_str = $action->get_power() . " (" . $ratio . "倍)";
          foreach ($rd_keys as $key2) {
            print "<td>";
            print $key1==$key2 ? $action_str : "&nbsp;";
            print "</td>";
          }
          $count = 1;
          foreach ($dices as $dice) {
            $ratio = $ratio > 6 ? 6 : $ratio;
            $judge = JudgementTable::judge($ratio, $dice);
            $rank = $count * $action->get_rank();
            $count++;
            if ($rank < 10000) {
              $action_output .= $rank . "\t" . $judge . "\t" . $results[$judge] . "\t" .$action->get_form() . "\t" . $ratio . "\n";
              $r_str = $results[$judge];
            } else {
              $r_str = "&nbsp;";
            }
            print "<td>";
            print $r_str;
            print "</td>";
          }
          print "</tr>";
        }
      }
    }
  }

  print "</table></p>";
}

?>

<p>
大：<?php print $results['大'] ?>
勝：<?php print $results['勝'] ?>
引：<?php print $results['引'] ?>
負：<?php print $results['負'] ?>
惨：<?php print $results['惨'] ?>
<br/>
数値は獲得勝利数、D1は1名死亡、DAは全滅の意味です。
</p>

<p>
評価結果（表計算ソフト入力用タブ区切り、ランク、判定、結果、提出能力、倍数）：<br/>
<form method="GET" action="<?php print($_SERVER['PHP_SELF']) ?>">
<textarea name="action_output" rows="4" cols="80">
<?php print $action_output; ?>
</textarea>
</form>
</p>
<hr/>
<p>
以下入力：<br/>
<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
<input type="submit" name="submit" value="解析する">
<br><br>
現有戦力： ※現状合算には対応してません<br/>
<textarea name="action_text" rows="4" cols="80">
<?php print $action_text; ?>
</textarea>
<br><br>
イベント記述：<br/>
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
