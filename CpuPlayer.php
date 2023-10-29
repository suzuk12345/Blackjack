<?php
namespace Blackjack;

require_once 'Player.php';

class CpuPlayer extends Player
{
    public function __construct(protected $name = "")
    {
    }

    // CPU:カードを二枚引く&表示*人数分
    public function cpuInitHand($deck, $cpuPlayerList, $numberOfCpu)
    {
        if ($numberOfCpu > 0) {
            for ($i = 0; $i < $numberOfCpu; $i++) {
                $cpuPlayerList[$i]->addCardAndScore($deck->drawACard());
                $cpuPlayerList[$i]->displayLastHand($cpuPlayerList[$i]->getName(), $cpuPlayerList[$i]->getHand());
                $cpuPlayerList[$i]->addCardAndScore($deck->drawACard());
                $cpuPlayerList[$i]->displayLastHand($cpuPlayerList[$i]->getName(), $cpuPlayerList[$i]->getHand());

                if ($this->score == 21) {
                    $this->blackjack = true;
                    echo "{$this->name}:ブラックジャック!".PHP_EOL;
                }
            }
        }
    }

    // CPU:得点が16以下の場合Hit(ソフト17でstand)
    public function cpuHitOrStand($cpuPlayerList, $deck, $numberOfCpu)
    {
        for ($i = 0; $i < $numberOfCpu; $i++) {
            for ($j = $cpuPlayerList[$i]->score; $j <= 16; $j = $cpuPlayerList[$i]->score) {
                if ($cpuPlayerList[$i]->aceCount >= 1) {
                    $soft = $cpuPlayerList[$i]->score - 10;
                    echo "{$cpuPlayerList[$i]->name}の現在の得点は{$cpuPlayerList[$i][$i]->score}({$soft})です。".PHP_EOL;
                } else {
                    echo "{$cpuPlayerList[$i]->name}の現在の得点は{$cpuPlayerList[$i]->score}です。".PHP_EOL;
                }

                $cpuPlayerList[$i]->addCardAndScore($deck->drawACard());
                $cpuPlayerList[$i]->displayLastHand($cpuPlayerList[$i]->name, $cpuPlayerList[$i]->hand);
            }
        }
    }
}
