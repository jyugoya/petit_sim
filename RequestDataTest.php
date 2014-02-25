<?php
require_once 'PHPUnit/Autoload.php';
require_once "RequestData.php";

/**
 * PHPUnit3を使用。
 * RequestDataクラスのテストケース
 * PHPUnit_Framework_TestCaseクラスを継承して作成する
 */
class RequestDataTest extends PHPUnit_Framework_TestCase
{
  /**
   * タグのみでの初期化のテスト
   */
  public function testDefaultInitialize() {
    $sut = new RequestData("タグのみ");

    $this->assertEquals("タグのみ", $sut->get_tag());
    $this->assertEquals(-1, $sut->get_power());
    $this->assertEquals(-1, $sut->get_snum());
  }

  /**
   * ただのGetter/Setterのテスト
   */
  public function testGetterSetter() {
    $sut = new RequestData("タグ",10, 5);

    // 初期値のテスト
    $this->assertEquals("タグ", $sut->get_tag());
    $this->assertEquals(10, $sut->get_power());
    $this->assertEquals(5, $sut->get_snum());

    // Setterで変更する
    $sut->set_tag("白兵");
    $sut->set_power(20);
    $sut->set_snum(10);

    // 変更後のテスト
    $this->assertEquals("白兵", $sut->get_tag());
    $this->assertEquals(20, $sut->get_power());
    $this->assertEquals(10, $sut->get_snum());
  }

}
