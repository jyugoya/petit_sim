<?php
require_once 'PHPUnit/Autoload.php';
require_once "JudgementTable.php";

/**
 * PHPUnit3を使用。
 * JudgementTableクラスのテストケース
 * PHPUnit_Framework_TestCaseクラスを継承して作成する
 */
class JudgementTableTest extends PHPUnit_Framework_TestCase
{
  /**
   * アイドレス３の判定表。
   * 出目　戦力比（X：１）
   * 　　　６５４３２１０　
   * １　　勝勝引引負負惨
   * ２　　大勝勝引負負惨
   * ３　　大勝勝引引負負
   * ４　　大大勝引引負負
   * ５　　大大勝勝引引負
   * ６　　大大大勝引引負
   */
  function testJudge() {
    // 戦力比0でダイス目が1の時は惨
    $this->assertEquals('惨', JudgementTable::judge(0, 1));

    // 戦力比3でダイス目が2の時は引
    $this->assertEquals('引', JudgementTable::judge(3, 2));

    // 戦力比1でダイス目が4の時は負
    $this->assertEquals('負', JudgementTable::judge(1, 4));

    // 戦力比0でダイス目が4の時は負
    $this->assertEquals('負', JudgementTable::judge(0, 4));

    // 戦力比6でダイス目が6の時は大
    $this->assertEquals('大', JudgementTable::judge(6, 6));
  }

  function testJudgeFull() {
    // 戦力比0でダイス目が1の時は惨敗
    $this->assertEquals('惨敗', JudgementTable::judge(0, 1, true));

    // 戦力比3でダイス目が2の時は引き分け
    $this->assertEquals('引き分け', JudgementTable::judge(3, 2, true));

    // 戦力比6でダイス目が6の時は大勝利
    $this->assertEquals('大勝利', JudgementTable::judge(6, 6, true));
  }
}
