<?php
require_once 'PHPUnit/Autoload.php';
require_once "EventParser.php";

/**
 * PHPUnit3を使用。
 * EventParserクラスのテストケース
 */
class EventParserTest extends PHPUnit_Framework_TestCase
{
  // タグ文字列の解析テスト（単一タグ版）
  public function testSingleTagParse() {
    $string = "要求タグ：知識";
    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();

    $this->assertEquals(-1, $rds['知識']->get_power());
    $this->assertEquals(-1, $rds['知識']->get_snum());
  }

  // タグ文字列の解析テスト（複数タグ版）
  public function testMultiTagsParse() {
    $string = "要求タグ：白兵、近距離、詠唱、装甲";
    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();

    $this->assertEquals(-1, $rds['白兵']->get_power());
    $this->assertEquals(-1, $rds['近距離']->get_power());
    $this->assertEquals(-1, $rds['詠唱']->get_power());
    $this->assertEquals(-1, $rds['装甲']->get_power());
  }

  // 戦力行の追加解析テスト（複数タグ版）
  public function testSingleTagWithPowerParse() {
    $string = "要求タグ：知識\n";
    $string .= "E＊戦力：１０";

    $sut = new EventParser();
    $sut->parse($string);
    $rds = $sut->get_rds();

    $this->assertEquals(10, $rds['知識']->get_power());
  }

  // 戦力行の追加解析テスト（複数タグ版）
  public function testMultiTagsWithPowerParse() {
    $string = "要求タグ：白兵、近距離、詠唱、装甲\n";
    $string .= "E＊戦力：白兵１５、近距離２１、詠唱２４、装甲３０";
    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();

    $this->assertEquals(15, $rds['白兵']->get_power());
    $this->assertEquals(21, $rds['近距離']->get_power());
    $this->assertEquals(24, $rds['詠唱']->get_power());
    $this->assertEquals(30, $rds['装甲']->get_power());
  }

  // 必要勝利数の解析テスト(単一タグ版)
  public function testSuccessRequestForSingleTagsParse() {
    $string = "要求タグ：知識\n";
    $string .= "必要勝利数：８";
    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();

    $this->assertEquals(8, $rds['知識']->get_snum());
  }

  // 必要勝利数の解析テスト(複数タグ版)
  public function testSuccessRequestForMultiTagsParse() {
    $string = "要求タグ：白兵、近距離、詠唱、装甲\n";
    $string .= "必要勝利数：白兵８、近距離３、詠唱１、装甲３";
    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();

    $this->assertEquals(8, $rds['白兵']->get_snum());
    $this->assertEquals(3, $rds['近距離']->get_snum());
    $this->assertEquals(1, $rds['詠唱']->get_snum());
    $this->assertEquals(3, $rds['装甲']->get_snum());
  }

  // ダイス目の解析テスト
  public function testDiceSequesceParse() {
    $string = "・ダイス出目は固定で、１、３、５、２、６、６、６、２、１、３である。";
    $sut = new EventParser();
    $sut->parse($string);
    $dices = $sut->get_dices();

    $this->assertEquals(1, $dices[0]);
    $this->assertEquals(2, $dices[3]);
    $this->assertEquals(3, $dices[9]);
  }

  public function testAll() {
    $string =<<<EOS
要求タグ：オペレート、攻撃機会、治療、外交戦
E＊戦力：オペレート３、攻撃機会５、治療１０、外交戦５
必要勝利数：オペレート１、攻撃機会１、治療５、外交戦１
フレーバー：ゆかりたちを倒した。味方の治療しながら走って脱出。さらに報酬をわけあわなければ。
特別ルール：
・D08をクリアしたメンバーだけが、D09に参加できる。
・ダイス出目は固定で、１、２、３、４、５、６、６、６、６、６である。
EOS;

    $sut = new EventParser();
    $sut->parse($string);

    $rds = $sut->get_rds();
    //print_r($rds);
    $this->assertEquals(5, $rds['外交戦']->get_power());
    $this->assertEquals(5, $rds['治療']->get_snum());

    $dices = $sut->get_dices();
    $this->assertEquals(4, $dices[3]);

  }

}
?>
