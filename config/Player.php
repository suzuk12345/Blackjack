<?php
namespace Blackjack;

class Player
{
    protected $name = 'あなた';
    protected $hand= [];
    protected $score = 0;
    protected $aceCount = 0;
    protected $blackjack = false;
    private $result = '';
    private $action = '';
    private $stand = false;

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

    // プレイヤーがブラックジャックかどうか取得
    public function getBlacjak()
    {
        return $this->blackjack;
    }

    // プレイヤーの勝敗結果をセット
    public function setWin()
    {
        $this->result = 'win';
    }

    public function setLose()
    {
        $this->result = 'lose';
    }

    public function setDraw()
    {
        $this->result = 'draw';
    }

    // プレイヤーの結果を取得
    public function getResult()
    {
        return $this->result;
    }


    // プレイヤーのアクションを取得
    public function getAction()
    {
        return $this->action;
    }

    // プレイヤーのアクションを設定
    public function setSurrender()
    {
        $this->action = 'surrender';
        $this->score = 999;
        $this->stand = true;
    }

    public function setDoubledown()
    {
        $this->action = 'doubledown';
        $this->stand = true;
    }

    public function setSplit()
    {
        $this->action = 'split';
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

        if ($this->score == 21) {
            $this->blackjack = true;
            echo "{$this->name}:ブラックジャック!".PHP_EOL;
        }
    }

    // プレイヤー:得点が20以下の場合Hit or Stand or Action
    public function hitOrStandOrAction($player, $deck, $chip)
    {
        Action::action($player, $deck, $chip);

        if (!($player->stand)) {
            for ($i = $player->score; $i <= 20; $i = $player->score) {
                if ($player->aceCount >= 1) {
                    $soft = $player->score - 10;
                    echo "{$player->name}の現在の得点は{$player->score}({$soft})です。カードを引きますか？（Y/N）".PHP_EOL;
                } else {
                    echo "{$player->name}の現在の得点は{$player->score}です。カードを引きますか？（Y/N）".PHP_EOL;
                }

                $input = strtoupper(trim(fgets(STDIN)));

                switch ($input) {
                    case 'Y':
                        $player->addCardAndScore($deck->drawACard());
                        $player->displayLastHand($player->name, $player->hand);
                        break;
                    case 'N':
                        $player->stand = true;
                        break;
                    default:
                        echo "入力が間違っています。".PHP_EOL;
                        echo "Y(Yes)もしくはN(No)を入力してください。".PHP_EOL;
                        break;
                }
                if ($player->stand) {
                    break;
                }
            }
        }
    }
}
