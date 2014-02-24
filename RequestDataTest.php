<?php
require_once 'PHPUnit2/Framework/TestCase.php';
require_once "RequestData.php";

/**
 * RequestDataクラスのテストケース
 * PHPUnit2_Framework_TestCaseクラスを継承して作成する
 */
class RequestDataTest extends PHPUnit2_Framework_TestCase
{
  private $sut_; // System Under Testing: テスト対象システム（JUnit実践入門より）

  public function RequestDataTest($name)
  {
    parent::__construct($name);
  }

  public function setUp()
  {
    $this->sut_ = new RequestData("タグ",10);
  }

  public function tearDown() {}

  /**
   * ただのGetter/Setterのテスト
   */
  public function testGetterSetter() {
    // 初期値のテスト
    $this->assertEquals("タグ", $this->sut_.get_tag());
    $this->assertEquals(10, $this->sut_.get_power());

    // Setterで変更する
    $this->sut_.set_name("白兵");
    $this->sut_.set_power(20);

    // 変更後のテスト
    $this->assertEquals("白兵", $this->sut_.get_tag());
    $this->assertEquals(20, $this->sut_.get_power());
  }

}
