<?php
require_once 'PHPUnit/Autoload.php';
require_once "PCAction.php";

/**
 * PHPUnit3を使用。
 * PCActionクラスのテストケース
 */
class PCActionTest extends PHPUnit_Framework_TestCase
{
  public function testInitialization() {
    // 初期化のテスト。
    // 書式文字列渡して、データに分割できるか。
    $sut = new PCAction("結城由羅【結城由羅のオペレート:1000:オペレート:38:3】");

    $this->assertEquals("結城由羅", $sut->get_owner());
    $this->assertEquals("結城由羅のオペレート", $sut->get_name());
    $this->assertEquals(1000, $sut->get_rank());
    $this->assertEquals("オペレート", $sut->get_tag());
    $this->assertEquals(38, $sut->get_power());
    $this->assertEquals(3, $sut->get_risk());
  }
}
?>
