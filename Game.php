<?php
namespace Blackjack;

require_once 'Deck.php';
require_once 'Player.php';
require_once 'Dealer.php';
require_once 'CpuPlayer.php';

class Game
{
    private $deck = '';
    private $player = '';
    private $dealer = '';
    private $numberOfCpu = 0;
    private $cpuPlayerList = [];

    public function start()
    {
        $this->deck = new Deck();
        $this->player = new Player();
        $this->dealer = new Dealer();

        // プレイヤー:カードを二枚引く&表示
        $this->player->initHand($this->deck);

        // CPU:カードを二枚引く&表示*人数分
        if ($this->numberOfCpu > 0) {
            $this->cpuPlayerList[0]->cpuInitHand($this->deck, $this->cpuPlayerList, $this->numberOfCpu);
        }

        // ディーラー:カードを2枚引く&一枚目のみ表示
        $this->dealer->initHand($this->deck);

        // プレイヤー:得点が20以下の場合Hit or Stand
        $this->player->hitOrStand($this->player, $this->deck);

        // CPU:得点が16以下の場合Hit(ソフト17でstand)*人数分
        if ($this->numberOfCpu > 0) {
            $this->cpuPlayerList[0]->cpuHitOrStand($this->cpuPlayerList, $this->deck, $this->numberOfCpu);
        }

        // ディーラー:2枚目のカード公開
        $this->dealer->openSecondHand($this->dealer->getName(), $this->dealer->getHand());

        // ディーラー:得点が16以下の場合Hit(ソフト17でstand)
        $this->dealer->hitOrStand($this->dealer, $this->deck);

        // ディーラー:全員の得点を表示
        $this->dealer->displayAllPlayerScores($this->player, $this->cpuPlayerList, $this->dealer, $this->numberOfCpu);

        // ディーラー:全員の勝敗判定
        $this->dealer->judge($this->player, $this->cpuPlayerList, $this->dealer, $this->numberOfCpu);

        // 終了
        echo 'ブラックジャックを終了します。';
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
    public function createCpuPlayer($numberOfCpu)
    {
        for ($i = 1; $i <= $numberOfCpu; $i++) {
            $this->cpuPlayerList[] = new CpuPlayer("CPU{$i}");
        }
    }

    // CPUの数を取得
    public function getNamberOfCpu()
    {
        return $this->numberOfCpu;
    }
}
