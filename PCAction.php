<?php
class PCAction {

  private $owner_;
  private $name_;
  private $rank_;
  private $tag_;
  private $power_;
  private $risk_;

  public function PCAction($string) {
    mb_regex_encoding("UTF-8");

    // ＰＣ名と行動を切り分ける
    preg_match('/([^【】]*)【([^：:]*)[：:]([^：:]*)[：:]([^：:]*)[：:]([^：:]*)[：:]([^：:]*)】/u', $string, $matches);
    if ($matches) {
      //print_r($matches);
      $this->owner_ = $matches[1];
      $this->name_ = $matches[2];
      $this->rank_ = $matches[3];
      $this->tag_ = $matches[4];
      $this->power_ = $matches[5];
      $this->risk_ = $matches[6];
    } else {
      $this->owner_ = "不明";
      $this->name_ = "";
      $this->rank_ = 0;
      $this->tag_ = "不明";
      $this->power_ = 0;
      $this->risk_ = 0;
    }
    
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
