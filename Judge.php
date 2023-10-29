<?php
namespace Blackjack;

class Judge
{
    // 勝敗判定
    public function judge($player, $cpuPlayer, $dealer, $numberOfCpu)
    {
        if ($player->getScore() > 21) { // プレイヤーのバースト負け
            echo "{$player->getname()}の負けです。".PHP_EOL;
            $player->setLose();
        } elseif ($dealer->getScore() > 21) { // ディーラーのバースト負け
            echo "{$player->getname()}の勝ちです!".PHP_EOL;
            $player->setWin();
        } elseif ($player->getScore() === $dealer->getScore()) {
            if ($player->getBlacjak() && !($dealer->getBlacjak())) {
                echo "{$player->getname()}の勝ちです!".PHP_EOL;
                $player->setWin();
            } elseif (!($player->getBlacjak()) && $dealer->getBlacjak()) {
                echo "{$player->getname()}の負けです。".PHP_EOL;
                $player->setLose();
            } else {
                echo "{$player->getname()}と{$dealer->getName()}は引き分けです。".PHP_EOL;
                $player->setDraw();
            }
        } elseif ($player->getScore() > $dealer->getScore()) {
            echo "{$player->getname()}の勝ちです!".PHP_EOL;
            $player->setWin();
        } elseif ($player->getScore() < $dealer->getScore()) {
            echo "{$player->getname()}の負けです。".PHP_EOL;
            $player->setLose();
        }

        if ($numberOfCpu > 0) {
            for ($i = 0; $i < $numberOfCpu; $i++) {
                if ($cpuPlayer[$i]->getScore() > 21) { // CPUのバースト負け
                    echo "{$cpuPlayer[$i]->getname()}の負けです。".PHP_EOL;
                } elseif ($dealer->getScore() > 21) { // ディーラーのバースト負け
                    echo "{$cpuPlayer[$i]->getname()}の勝ちです!".PHP_EOL;
                } elseif ($cpuPlayer[$i]->getScore() === $dealer->getScore()) {
                    if ($cpuPlayer[$i]->getBlacjak() && !($dealer->getBlacjak())) {
                        echo "{$cpuPlayer[$i]->getname()}の勝ちです!".PHP_EOL;
                    } elseif (!($cpuPlayer[$i]->getBlacjak()) && $dealer->getBlacjak()) {
                        echo "{$cpuPlayer[$i]->getname()}の負けです。".PHP_EOL;
                    } else {
                        echo "{$cpuPlayer[$i]->getname()}と{$dealer->getName()}は引き分けです。".PHP_EOL;
                    }
                } elseif ($cpuPlayer[$i]->getScore() > $dealer->getScore()) {
                    echo "{$cpuPlayer[$i]->getname()}の勝ちです!".PHP_EOL;
                } elseif ($cpuPlayer[$i]->getScore() < $dealer->getScore()) {
                    echo "{$cpuPlayer[$i]->getname()}の負けです。".PHP_EOL;
                }
            }
        }
    }
}