<?php
require_once 'Player.php';
class Dealer extends Player
{
    protected $name = 'ディーラー';

    // ディーラーの2枚目のカードは表示しない
    public function holeCard()
    {
        echo 'ディーラーの引いた2枚目のカードはわかりません。'.PHP_EOL;
    }

    // ディーラーの2枚目のカードを表示
    public function openSecondHand($name, $hands)
    {
        $lastHand = array_slice($hands, -1, 1);
        echo "{$name}の引いた2枚目のカードは".$lastHand[0][0].'の'.$lastHand[0][1].'でした。'.PHP_EOL;
    }

    // ディーラー:得点が16以下の場合Hit(ソフト17でstand)
    public function hitOrStand($dealer, $deck)
    {
        for ($i = $dealer->score; $i <= 16; $i = $dealer->score) {
            if ($dealer->aceCount >= 1) {
                $soft = $dealer->score - 10;
                echo "ディーラーの現在の得点は{$dealer->score}({$soft})です。".PHP_EOL;
            } else {
                echo "ディーラーの現在の得点は{$dealer->score}です。".PHP_EOL;
            }

            $dealer->addCardAndScore($deck->drawACard());
            $dealer->displayLastHand($dealer->name, $dealer->hands);
        }
    }
}
