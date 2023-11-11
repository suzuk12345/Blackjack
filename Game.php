<?php
namespace Blackjack;

require_once 'config/Deck.php';
require_once 'config/Player.php';
require_once 'config/Dealer.php';
require_once 'config/CpuPlayer.php';
require_once 'config/Judge.php';
require_once 'config/Action.php';
require_once 'config/Chip.php';
require_once 'config/Setting.php';

class Game
{
    private $deck;
    private $player;
    private $dealer;
    private $chip;
    private $setting;
    private $cpuPlayerList = [];

    public function start()
    {
        $this->chip = new Chip();
        $this->setting = new Setting();

        // CPUの数を設定
        $this->setting->setNumberOfCpuPlayer();

        while (true) {
            // デッキ、プレイヤー初期化
            $this->deck = new Deck();
            $this->player = new Player();
            $this->dealer = new Dealer();
            $this->cpuPlayerList = [];

            // 設定した分CPUインスタンスを生成
            for ($i = 1; $i <= $this->setting->getNumOfCpu(); $i++) {
                $this->cpuPlayerList[] = new CpuPlayer("CPU{$i}");
            }

            // プレイヤー:賭けるチップを選択
            $this->chip->playerBet();

            // プレイヤー:カードを二枚引く&表示
            $this->player->initHand($this->deck);

            // CPU:カードを二枚引く&表示*人数分
            if ($this->setting->getNumOfCpu() > 0) {
                $this->cpuPlayerList[0]->cpuInitHand($this->deck, $this->cpuPlayerList, $this->setting->getNumOfCpu());
            }

            // ディーラー:カードを2枚引く&一枚目のみ表示
            $this->dealer->initHand($this->deck);

            // プレイヤー:得点が20以下の場合Hit or Stand or Action
            $this->player->hitOrStandOrAction($this->player, $this->deck, $this->chip);

            // CPU:得点が16以下の場合Hit(ソフト17でstand)*人数分
            if ($this->setting->getNumOfCpu() > 0) {
                $this->cpuPlayerList[0]->cpuHitOrStand($this->cpuPlayerList, $this->deck, $this->setting->getNumOfCpu());
            }

            // ディーラー:2枚目のカード公開
            $this->dealer->openSecondHand($this->dealer->getName(), $this->dealer->getHand());

            // ディーラー:得点が16以下の場合Hit(ソフト17でstand)
            $this->dealer->hitOrStand($this->dealer, $this->deck);

            // ディーラー:全員の得点を表示
            $this->dealer->displayAllScores($this->player, $this->cpuPlayerList, $this->dealer, $this->setting->getNumOfCpu());

            // ディーラー:全員の勝敗判定と配当支払い
            Judge::judge($this->player, $this->cpuPlayerList, $this->dealer, $this->setting->getNumOfCpu());
            $this->chip->payout($this->player);

            // チップがなくなったら(10以下)強制終了
            if ($this->chip->getPlayerFund() < 10) {
                echo 'チップがなくなりました。ブラックジャックを終了します。';
                break;
            }
            // 終了判断
            $this->setting->continueOrNot();
            if ($this->setting->getEndFlag()) {
                echo 'ブラックジャックを終了します。';
                break;
            }
        }
    }
}
