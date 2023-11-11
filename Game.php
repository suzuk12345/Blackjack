<?php
namespace Blackjack;

require_once 'config/Deck.php';
require_once 'config/Player.php';
require_once 'config/Dealer.php';
require_once 'config/CpuPlayer.php';
require_once 'config/Judge.php';
require_once 'config/Action.php';
require_once 'config/Chip.php';

class Game
{
    private $deck;
    private $player;
    private $dealer;
    private $numberOfCpu = 0;
    private $chip;
    private $cpuPlayerList = [];
    private $end = false;

    public function start()
    {
        // $this->judge = new judge();
        $this->chip = new Chip();

        // CPUの数を設定
        $this->setNumberOfCpuPlayer();

        while (true) {
            // デッキ、プレイヤー初期化
            $this->deck = new Deck();
            $this->player = new Player();
            $this->dealer = new Dealer();
            $this->cpuPlayerList = [];

            // 設定した分CPUを生成
            $this->createCpuPlayer();

            $this->chip->playerBet();

            // プレイヤー:カードを二枚引く&表示
            $this->player->initHand($this->deck);

            // CPU:カードを二枚引く&表示*人数分
            if ($this->numberOfCpu > 0) {
                $this->cpuPlayerList[0]->cpuInitHand($this->deck, $this->cpuPlayerList, $this->numberOfCpu);
            }

            // ディーラー:カードを2枚引く&一枚目のみ表示
            $this->dealer->initHand($this->deck);

            // プレイヤー:得点が20以下の場合Hit or Stand or Action
            $this->player->hitOrStandOrAction($this->player, $this->deck, $this->chip);

            // CPU:得点が16以下の場合Hit(ソフト17でstand)*人数分
            if ($this->numberOfCpu > 0) {
                $this->cpuPlayerList[0]->cpuHitOrStand($this->cpuPlayerList, $this->deck, $this->numberOfCpu);
            }

            // ディーラー:2枚目のカード公開
            $this->dealer->openSecondHand($this->dealer->getName(), $this->dealer->getHand());

            // ディーラー:得点が16以下の場合Hit(ソフト17でstand)
            $this->dealer->hitOrStand($this->dealer, $this->deck);

            // ディーラー:全員の得点を表示
            $this->dealer->displayAllScores($this->player, $this->cpuPlayerList, $this->dealer, $this->numberOfCpu);

            // ディーラー:全員の勝敗判定と配当支払い
            Judge::judge($this->player, $this->cpuPlayerList, $this->dealer, $this->numberOfCpu);
            $this->chip->payout($this->player);

            // チップがなくなったら(10以下)強制終了
            if ($this->chip->getPlayerFund() < 10) {
                echo 'チップがなくなりました。ブラックジャックを終了します。';
                break;
            }
            // 終了判断
            $this->continueOrNot();
            if ($this->end) {
                echo 'ブラックジャックを終了します。';
                break;
            }
        }
    }

    // CPUの数を選択(0~2)
    public function setNumberOfCpuPlayer()
    {
        echo 'ブラックジャックを開始します。'.PHP_EOL;
        echo 'CPUの数を選択してください。(0~2)'.PHP_EOL;

        while (true) {
            $this->numberOfCpu = trim(fgets(STDIN));

            if (preg_match('/^[0-2]$/', $this->numberOfCpu)) {
                $this->numberOfCpu = (int)$this->numberOfCpu;
                break;
            }

            echo '0~2の内から選択してください。'.PHP_EOL;
        }
    }

    // $numberOfCpuの数の分だけCPUインスタンスを作成
    public function createCpuPlayer()
    {
        for ($i = 1; $i <= $this->numberOfCpu; $i++) {
            $this->cpuPlayerList[] = new CpuPlayer("CPU{$i}");
        }
    }

    // 終了判断
    public function continueOrNot()
    {
        $loop = true;
        while ($loop) {
            echo '続けますか?(Y/N)'.PHP_EOL;
            $input = strtoupper(trim(fgets(STDIN)));
            switch ($input) {
                case 'Y':
                    $loop = false;
                    break;
                case 'N':
                    $this->end = true;
                    $loop = false;
                    break;
                default:
                    echo "入力が間違っています。".PHP_EOL;
                    echo "Y(Yes)もしくはN(No)を入力してください。".PHP_EOL;
                    break;
            }
        }
    }
}
