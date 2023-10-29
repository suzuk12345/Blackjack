<?php
namespace Blackjack;

class Chip
{
    private $playerFund = 1000;
    private $playerStake = 0;

    public function playerBet()
    {
        while (true) {
            echo "賭けるチップをチップ残高の内から10の倍数で指定してください。(残高:{$this->playerFund})".PHP_EOL;
            $input = trim(fgets(STDIN));
            $input = (int)$input;
            if ($input >= 10 && $input <= $this->playerFund && $input % 10 === 0) {
                $this->playerFund -= $input;
                $this->playerStake = $input;
                echo "賭けたチップは{$this->playerStake}です。(残高:{$this->playerFund})".PHP_EOL;
                break;
            }
        }
    }

    public function payout($player)
    {
        switch ($player->getResult()) {
            case 'win':
                if ($player->getBlacjak()) {
                    echo "ブラックジャック/勝ち:賭けたチップ({$this->playerStake})と1.5倍の配当が支払われます。".PHP_EOL;
                    $this->playerFund += $this->playerStake * 2.5;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                } else {
                    echo "勝ち:賭けたチップ({$this->playerStake})と等倍の配当が支払われます。".PHP_EOL;
                    $this->playerFund += $this->playerStake * 2;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                }
            case 'lose':
                echo "負け:賭けたチップ({$this->playerStake})は没収されます。".PHP_EOL;
                $this->playerStake = 0;
                echo "チップ残高:{$this->playerFund}".PHP_EOL;
                break;
            case 'draw':
                echo "引き分け:賭けたチップ({$this->playerStake})は返却されます。".PHP_EOL;
                $this->playerFund += $this->playerStake;
                $this->playerStake = 0;
                echo "チップ残高:{$this->playerFund}".PHP_EOL;
                break;
            default:
                break;
        }
    }

    public function getPlayerFund()
    {
        return $this->playerFund;
    }
}
