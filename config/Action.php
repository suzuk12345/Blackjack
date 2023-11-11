<?php
namespace Blackjack;

class Action
{
    public static function action($player, $deck, $chip)
    {
        $loop = true;
        while ($loop) {
            echo "アクションを行いますか?(Y/N)".PHP_EOL;
            $input = trim(fgets(STDIN));
            switch ($input) {
                case 'Y':
                    self::option($player, $deck, $chip);
                    $loop = false;
                    break;
                case 'N':
                    $loop = false;
                    break;
                default:
                    echo "入力が間違っています".PHP_EOL;
                    echo "Y(Yes)もしくはN(No)を入力してください。".PHP_EOL;
                    break;
            }
        }
    }

    public static function option($player, $deck, $chip)
    {
        // if ($player->hand[0][0][2] == $player->hand[0][1][2]) {

        // }
        $loop = true;
        while ($loop) {
            echo 'サレンダー(SR)、ダブルダウン(DD)、やめる(C)の内から選んで実行してください。(SR/DD/C)'.PHP_EOL;
            $input = trim(fgets(STDIN));
            switch ($input) {
                case 'SR':
                    self::surrender($player);
                    $loop = false;
                    break;
                case 'DD':
                    if ($chip->getPlayerFund() < $chip->getPlayerStake()) {
                        echo 'チップ残高が不足しているためダブルダウンはできません。'.PHP_EOL;
                        break;
                    }
                    self::doubledown($player, $deck, $chip);
                    $loop = false;
                    break;
                case 'C':
                    $loop = false;
                    break;
                default:
                    echo "入力が間違っています".PHP_EOL;
                    break;
            }
        }
    }

    public static function surrender($player)
    {
        echo 'サレンダーしました。'.PHP_EOL;
        $player->setSurrender();
    }

    public static function doubledown($player, $deck, $chip)
    {
        $chip->processDoubledown();
        echo "ダブルダウンします。(賭けたチップ:{$chip->getPlayerStake()}/残高:{$chip->getPlayerFund()})".PHP_EOL;
        $player->setDoubledown();
        $player->addCardAndScore($deck->drawACard());
        $player->displayLastHand($player->getName(), $player->getHand());
    }
}
