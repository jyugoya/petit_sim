<?php
/**
 * イベント文字列のパーサ。
 * 渡された文字列から以下のようなデータを生成してとりあえず自分ところに格納する。
 * ・要求データ（タグと戦力）のリスト
 */
function mb_trim( $string ) 
{ 
    mb_regex_encoding("UTF-8");
    $string = preg_replace( "/(^\s+)|(\s+$)/us", "", $string ); 
    
    return $string; 
} 

class EventParser {

  private $rds_ = array(); // 要求タグと要求戦力
  private $dices_ = array(); // ダイス目列
  private $srs_ = array(); // タグの必要勝利数

  public function get_rds() {
    return $this->rds_;
  }

  public function get_dices() {
    return $this->dices_;
  }

  public function get_srs() {
    return $this->srs_;
  }

  public function parse($string) {
    //echo mb_detect_encoding($string);
    mb_regex_encoding("UTF-8");
    $lines = mb_split('\n', $string);

    $tags = array();
    $powers = array();
    $snums = array();
    foreach ($lines as $line) {
      $line = mb_trim($line);
      // タグ列の切り出し
      preg_match('/要求タグ：(.*)/u',$line,$matches);
      //print_r($matches);
      if ($matches) {
        $tags = mb_split('、', $matches[1]);
        //print_r($tags);
      }

      // 戦力列の切り出し
      preg_match('/E＊戦力：(.*)/u',$line,$matches);
      if ($matches) {
        $powers = mb_split('、', $matches[1]);
      }

      // 必要勝利数列の切り出し
      preg_match('/必要勝利数：(.*)/u',$line,$matches);
      if ($matches) {
        $snums = mb_split('、', $matches[1]);
      }

      // 固定ダイス列の切り出し
      preg_match('/ダイス出目は固定で、(.*)である/u',$line,$matches);
      // 先に全角→半角変換
      if ($matches) {
        $d_str = mb_convert_kana($matches[1],'a',"UTF-8");
        //echo $d_str;
        $this->dices_ = mb_split('、', $d_str);
        //print_r($this->dices_);
      }

    }

    //print_r($tags);
    //print_r($powers);

    // 戦力初期値は-1(不明)
    // 必要勝利数初期値は-1(不明)
    foreach ($tags as $tag) {
      //print $tag . ",";
      $this->rds_[$tag] = -1;
      $this->srs_[$tag] = -1;
    }

    foreach ($powers as $power) {
      // 英数字の半角化
      $power = mb_convert_kana($power,'a',"UTF-8");
      // 末尾が数字の場合タグと数値に分ける
      preg_match('/([^\d]*)(\d+)$/u',$power,$matches);
      if ($matches) {
        //print_r($matches);
        $tag = $matches[1];
        $this->rds_[$tag] = $matches[2];
      }
    }

    foreach ($snums as $snum) {
      // 英数字の半角化
      $snum = mb_convert_kana($snum,'a',"UTF-8");
      // 末尾が数字の場合タグと数値に分ける
      preg_match('/([^\d]*)(\d+)$/u',$snum,$matches);
      if ($matches) {
        //print_r($matches);
        $tag = $matches[1];
        $this->srs_[$tag] = $matches[2];
      }
    }
  }

}
?>
