<?php
class PCAction {

  private $owner_;
  private $name_;
  private $rank_;
  private $tag_;
  private $power_;
  private $risk_;

  public function PCAction($owner="不明", $name="不明", $rank=0, $tag="不明", $power=0, $risk=0) {
      $this->owner_ = $owner;
      $this->name_ = $name;
      $this->rank_ = $rank;
      $this->tag_ = $tag;
      $this->power_ = $power;
      $this->risk_ = $risk;
  }

  public function get_owner() {
    return $this->owner_;
  }

  public function get_name() {
    return $this->name_;
  }

  public function get_rank() {
    return $this->rank_;
  }

  public function get_tag() {
    return $this->tag_;
  }

  public function get_power() {
    return $this->power_;
  }

  public function get_risk() {
    return $this->risk_;
  }
}
?>
