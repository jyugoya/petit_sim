<?php
require_once "RequestData.php";

// 判定表


// タグ列の切り出し
$string = "要求タグ：白兵、近距離、詠唱、装甲";
preg_match('/要求タグ：(.*)/',$string,$matches);
//print_r($matches);
$tags = explode('、', $matches[1]);
//print_r($tags);

// 戦力列の切り出し
$string = "E＊戦力：白兵１５、近距離２１、詠唱２４、装甲３０";
preg_match('/E＊戦力：(.*)/',$string,$matches);
$powers = explode('、', $matches[1]);
//print_r($powers);

// 固定ダイス列の切り出し
$string = "・ダイス出目は固定で、１、３、５、２、６、６、６、２、１、３である。";
preg_match('/ダイス出目は固定で、(.*)である/',$string,$matches);
// 先に全角→半角変換
$d_str = mb_convert_kana($matches[1],'a',"UTF-8");
//echo $d_str;
$dices = explode('、', $d_str);
//print_r($dices);

// 要求データに変換
$rds = array();
foreach ($tags as $tag) {
  foreach ($powers as $power) {
    $pattern = '/' . $tag  . '(.*)/';
    preg_match($pattern,$power,$matches);
    if ($matches) {
      //print_r($matches);
      $p_val = mb_convert_kana($matches[1],'a',"UTF-8");
      //echo $tag;
      //echo $p_val;
      $rds[] = new RequestData($tag, $p_val);
    }
  }
}
//print_r($rds);
?>
