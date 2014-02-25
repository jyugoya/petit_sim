<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>簡易イベントシミュレータ</title></head>
<body>

<p>簡易イベントシミュレータ</p>

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

if (isset($_POST['event_text'])) {
  $event_text = $_POST['event_text'];
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

  print "<tr align=\"center\">";
  print "<th>必要勝利数</th>";
  foreach ($rd_keys as $key) {
    print "<th>" . $rds[$key]->get_snum() . "</th>";
  }
  foreach ($dices as $dice) {
    print "<th>" . "&nbsp;" . "</th>";
  }
  print "</tr>";

  print "</table></p>";

}

?>
<p>
<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
<textarea name="event_text" rows="16" cols="80">
<?php print $event_text; ?>
</textarea><br><br>
<input type="submit" name="submit" value="解析する">
</form>
</p>

</body></html>
