<?php
/**
 * ものすごく適当に要求データ格納場所を作る。
 * 要求タグと相手戦力のペア。
 */
class RequestData {
  private $req_tag_;
  private $req_power_;

  public function RequestData($tag, $power) {
    $this->req_tag_ = $tag;
    $this->req_power_ = $power;
  }

  public function get_tag() {
    return $this->req_tag_;
  }

  public function get_power() {
    return $this->req_power_;
  }

  public function set_tag($tag) {
    $this->req_tag_ = $tag;
  }

  public function set_power($power) {
    $this->req_power_ = $power;
  }

}
?>
