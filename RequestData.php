<?php
/**
 * ものすごく適当に要求データ格納場所を作る。
 * 要求タグと相手戦力、および必要勝利数。
 */
class RequestData {
  // 要求タグ (Reqested Tag)
  private $req_tag_;

  // タグの対象戦力 (Target's Power)
  private $req_power_;

  // タグの必要勝利数 (Required Number of Successes)
  private $req_snum_;

  // タグ以外は指定がない場合は-1（不明）
  public function RequestData($tag, $power=-1, $snum=-1) {
    $this->req_tag_   = $tag;
    $this->tgt_power_ = $power;
    $this->req_snum_  = $snum; 
  }

  public function get_tag() {
    return $this->req_tag_;
  }

  public function get_power() {
    return $this->tgt_power_;
  }

  public function get_snum() {
    return $this->req_snum_;
  }

  public function set_tag($tag) {
    $this->req_tag_ = $tag;
  }

  public function set_power($power) {
    $this->tgt_power_ = $power;
  }

  public function set_snum($snum) {
    $this->req_snum_ = $snum;
  }

}
?>
