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
   * ただのGetter/Setterのテスト
   */
  public function testGetterSetter() {
    $sut = new RequestData("タグ",10);

    // 初期値のテスト
    $this->assertEquals("タグ", $sut->get_tag());
    $this->assertEquals(10, $sut->get_power());

    // Setterで変更する
    $sut->set_tag("白兵");
    $sut->set_power(20);

    // 変更後のテスト
    $this->assertEquals("白兵", $sut->get_tag());
    $this->assertEquals(20, $sut->get_power());
  }

}
