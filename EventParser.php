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

  private $tgt_powers_ = array(); // タグごとの対象戦力(Target Power)
  private $req_succs_ = array();  // タグごとの必要勝利数(Required Successes)
  private $dices_ = array();      // ダイス目列(Dice Sequence)

  public function get_tgt_powers() {
    return $this->tgt_powers_;
  }

  public function get_dices() {
    return $this->dices_;
  }

  public function get_req_succs() {
    return $this->req_succs_;
  }


  // タグ列の切り出し(parse メソッドで使用)
  private function parse_tags(&$tags, $line) {
    mb_regex_encoding("UTF-8");
    preg_match('/要求タグ：(.*)/u',$line,$matches);
    //print_r($matches);
    if ($matches) {
      $tags = mb_split('、', $matches[1]);
      //print_r($tags);
    }
  }

  // 戦力列の切り出し(parse メソッドで使用)
  private function parse_powers(&$powers, $line) {
    mb_regex_encoding("UTF-8");
    preg_match('/E＊戦力：(.*)/u',$line,$matches);
    if ($matches) {
      $powers = mb_split('、', $matches[1]);
    }
  }

  // 必要勝利数列の切り出し(parse メソッドで使用)
  private function parse_snums(&$snums, $line) {
    mb_regex_encoding("UTF-8");
    preg_match('/必要勝利数：(.*)/u',$line,$matches);
    if ($matches) {
      $snums = mb_split('、', $matches[1]);
    }
  }

  private function parse_dices(&$dices, $line) {
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

  // 解析器本体
  public function parse($string) {
    //echo mb_detect_encoding($string);
    mb_regex_encoding("UTF-8");
    $lines = mb_split('\n', $string);

    $tags = array();
    $powers = array();
    $snums = array();
    foreach ($lines as $line) {
      $line = mb_trim($line);

      $this->parse_tags($tags, $line); // $tags は参照渡し
      $this->parse_powers($powers, $line); // $powers は参照渡し
      $this->parse_snums($snums, $line); // $snums は参照渡し
      $this->parse_dices($dices, $line); // $dices は参照渡し
    }

    //print_r($tags);
    //print_r($powers);

    // 戦力初期値は-1(不明)
    // 必要勝利数初期値は-1(不明)
    foreach ($tags as $tag) {
      //print $tag . ",";
      $this->tgt_powers_[$tag] = -1;
      $this->req_succs_[$tag] = -1;
    }

    foreach ($powers as $power) {
      // 英数字の半角化
      $power = mb_convert_kana($power,'a',"UTF-8");
      // 末尾が数字の場合タグと数値に分ける
      preg_match('/([^\d]*)(\d+)$/u',$power,$matches);
      if ($matches) {
        //print_r($matches);
        $tag = $matches[1];
        $this->tgt_powers_[$tag] = $matches[2];
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
        $this->req_succs_[$tag] = $matches[2];
      }
    }
  }

}
?>
