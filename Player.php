<?php
namespace Blackjack;

class Player
{
    protected $name = 'あなた';
    protected $hand = [];
    protected $score = 0;
    protected $aceCount = 0;

    // プレイヤーの名前を取得
    public function getName()
    {
        return $this->name;
    }

    // プレイヤーの手札を取得
    public function getHand()
    {
        return $this->hand;
    }

    // プレイヤーのスコアを取得
    public function getScore()
    {
        return $this->score;
    }


    // 引いたカード(1枚)を手札に加える&得点を記録する
    public function addCardAndScore($drawnCard)
    {
        if ($drawnCard[1] === 'A') {
            $this->aceCount++;
        }
        $this->hand[] = $drawnCard;
        $this->score += $drawnCard[2];
        if ($this->aceCount >= 1 && $this->score >= 22) {
            $this->score -= 10;
            $this->aceCount--;
        }
    }

    // 手札の最後の一枚を表示
    public function displayLastHand($name, $hand)
    {
        $lastHand = array_slice($hand, -1, 1);
        echo "{$name}の引いたカードは、".$lastHand[0][0].'の'.$lastHand[0][1].'です。'.PHP_EOL;
    }

    // 手札を二枚引く&表示
    public function initHand($deck)
    {
        $this->addCardAndScore($deck->drawACard());
        $this->displayLastHand($this->getName(), $this->getHand());
        $this->addCardAndScore($deck->drawACard());
        $this->displayLastHand($this->getName(), $this->getHand());
    }

    // プレイヤー:得点が20以下の場合Hit or Stand
    public function hitOrStand($player, $deck)
    {
        for ($i = $player->score; $i <= 20; $i = $player->score) {
            $stand = false;
            if ($player->aceCount >= 1) {
                $soft = $player->score - 10;
                echo "あなたの現在の得点は{$player->score}({$soft})です。カードを引きますか？（Y/N）";
            } else {
                echo "あなたの現在の得点は{$player->score}です。カードを引きますか？（Y/N）";
            }

            $input = trim(fgets(STDIN));

            switch ($input) {
                case 'Y':
                    $player->addCardAndScore($deck->drawACard());
                    $player->displayLastHand($player->name, $player->hand);
                    break;
                case 'N':
                    $stand = true;
                    break;
                default:
                    break;
            }
            if ($stand) {
                break;
            }
        }
    }
}
