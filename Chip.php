<?php
namespace Blackjack;

class Chip
{
    private $playerFund = 1000;
    private $playerStake = 0;

    // 賭けるチップの額を決定
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

    // 配当支払い
    public function payout($player)
    {
        switch ($player->getResult()) {
            case 'win':
                if ($player->getBlacjak()) {
                    echo "ブラックジャック/勝ち:賭けたチップ({$this->playerStake})+1.5倍の配当が支払われます。".PHP_EOL;
                    $this->playerFund += $this->playerStake * 2.5;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                } else {
                    echo "勝ち:賭けたチップ({$this->playerStake})+等倍の配当が支払われます。".PHP_EOL;
                    $this->playerFund += $this->playerStake * 2;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                }
            case 'lose':
                if ($player->getAction() == 'surrender') {
                    echo "サレンダー/負け:賭けたチップ({$this->playerStake})の半分が返却されます。".PHP_EOL;
                    $this->playerFund += $this->playerStake / 2;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                } else {
                    echo "負け:賭けたチップ({$this->playerStake})は没収されます。".PHP_EOL;
                    $this->playerStake = 0;
                    echo "チップ残高:{$this->playerFund}".PHP_EOL;
                    break;
                }
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

    // チップの残高を取得
    public function getPlayerFund()
    {
        return $this->playerFund;
    }

    // 賭けているチップの額を取得
    public function getPlayerStake()
    {
        return $this->playerStake;
    }

    //ダブルダウンの処理
    public function processDoubledown()
    {
        $this->playerFund -= $this->playerStake;
        $this->playerStake *= 2;
    }
}
