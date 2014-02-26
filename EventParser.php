<?php
require_once "RequestData.php";
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

  // RequestData のリスト
  private $rds_ = array();

  // ダイス目のリスト
  private $dices_ = array();      // ダイス目列(Dice Sequence)

  // 判定結果の表
  private $results_ = array();

  public function get_rds() {
    return $this->rds_;
  }

  public function get_dices() {
    return $this->dices_;
  }

  public function get_results() {
    return $this->results_;
  }


  // 解析器本体
  public function parse($string) {
    //echo mb_detect_encoding($string);
    mb_regex_encoding("UTF-8");
    $lines = mb_split('\n', $string);

    // 入力文字列処理
    $tags = array();
    $powers = array();
    $snums = array();
    foreach ($lines as $line) {
      $line = mb_trim($line);

      $this->parse_tags($tags, $line); // $tags は参照渡し
      $this->parse_powers($powers, $line); // $powers は参照渡し
      $this->parse_snums($snums, $line); // $snums は参照渡し
      $this->parse_dices($line);
      $this->parse_results($line);
    }

    // タグごとにRequestDataを作成
    // 戦力初期値および必要勝利数初期値は-1(不明)
    foreach ($tags as $tag) {
      $this->rds_[$tag] = new RequestData($tag);
    }
    //print_r($this->tgt_powers_);
    //print_r($this->req_snum_);

    // 対象戦力値のセット
    foreach ($powers as $power) {
      // 英数字の半角化
      $power = mb_convert_kana($power,'a',"UTF-8");
      // 末尾が数字の場合タグと数値に分ける
      preg_match('/([^\d]*)(\d+)$/u',$power,$matches);
      if ($matches) {
        $tag = $matches[1];
        // タグが存在しない（数値だけの場合）は、全タグに同じ数値をセットする
        if (empty($tag)) {
          foreach ($this->rds_ as $rd) {
            $rd->set_power($matches[2]);
          }
        } else {
            $this->rds_[$tag]->set_power($matches[2]);
        }
      }
    }

    // 必要勝利数のセット
    // 複数タグで合計の場合はとりあえず前の奴に押し込んで他は0に…
    foreach ($snums as $snum) {
      // 英数字の半角化
      $snum = mb_convert_kana($snum,'a',"UTF-8");
      // 末尾が数字の場合タグと数値に分ける
      preg_match('/([^\d]*)(\d+)$/u',$snum,$matches);
      if ($matches) {
        $tag = $matches[1];
        if (empty($tag)) {
          $count = 0;
          foreach ($this->rds_ as $rd) {
            $rd->set_snum($count == 0 ? $matches[2] : 0);
            $count++;
          }
        } else {
            $this->rds_[$tag]->set_snum($matches[2]);
        }
      }
    }

  }

  // 以下、parseで使用するprivateメソッド群
  // タグ列の切り出し(parse メソッドで使用)
  private function parse_tags(&$tags, $line) {
    mb_regex_encoding("UTF-8");
    preg_match('/要求タグ：(.*)/u',$line,$matches);
    if ($matches) {
      $tags = mb_split('、', $matches[1]);
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

  // 固定ダイス列の切り出し(parse メソッドで使用)
  private function parse_dices($line) {
    mb_regex_encoding("UTF-8");
    preg_match('/ダイス出目は固定で、(.*)である/u',$line,$matches);
    // 先に全角→半角変換
    if ($matches) {
      $d_str = mb_convert_kana($matches[1],'a',"UTF-8");
      $this->dices_ = mb_split('、', $d_str);
    }
  }

  // 結果列の切り出し(parse メソッドで使用)
  private function parse_results($line) {
    mb_regex_encoding("UTF-8");
    // とりあえず先に全角数字を半角へ
    $line = mb_convert_kana($line,'a',"UTF-8");
    //print_r($line);
    $keys = array ( '大勝利' => '大', '勝利' => '勝', '引き分け' => '引', '敗北' => '負', '惨敗' => '惨' );
    preg_match('/^(大勝利|勝利|引き分け|敗北|惨敗)時の効果:(-?\d+)勝利を得る/u',$line,$matches);
    if ($matches) { 
      $this->results_[$keys[$matches[1]]] = $matches[2];
    }
    preg_match('/^(大勝利|勝利|引き分け|敗北|惨敗)時の効果:なし/u',$line,$matches);
    if ($matches) {
      //print_r($matches);
      $this->results_[$keys[$matches[1]]] = 0;
    }
    preg_match('/^(大勝利|勝利|引き分け|敗北|惨敗)時の効果:パーティの中から(\d+)名死亡/u',$line,$matches);
    if ($matches) {
      //print_r($matches);
      $this->results_[$keys[$matches[1]]] = 'D' . $matches[2];
    }
    preg_match('/^(大勝利|勝利|引き分け|敗北|惨敗)時の効果:パーティ全滅、全員死亡/u',$line,$matches);
    if ($matches) {
      //print_r($matches);
      $this->results_[$keys[$matches[1]]] = 'DA';
    }
  }
}
?>
