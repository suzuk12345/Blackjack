<?php
require_once 'Deck.php';
require_once 'Player.php';
require_once 'Dealer.php';

class Game
{
    public function start()
    {
        $deck = new Deck();
        $player = new Player();
        $dealer = new Dealer();

        echo 'ブラックジャックを開始します。'.PHP_EOL;
        // プレイヤー:カードを二枚引く&表示
        $player->addCardAndScore($deck->drawACard());
        $this->displayLastHand($player->getName(), $player->getHands());
        $player->addCardAndScore($deck->drawACard());
        $this->displayLastHand($player->getName(), $player->getHands());

        // ディーラー:カードを2枚引く&一枚目のみ表示
        $dealer->addCardAndScore($deck->drawACard());
        $this->displayLastHand($dealer->getName(), $dealer->getHands());
        $dealer->addCardAndScore($deck->drawACard());
        $this->holeCard();

        // プレイヤー:得点が20以下の場合Hit or Stand
        $this->playerHitOrStand($player, $deck);

        // ディーラー:2枚目のカード公開
        $this->openSecondHand($dealer->getName(), $dealer->getHands());

        // ディーラー:得点が16以下の場合Hit
        $this->dealerHitOrStand($dealer, $deck);

        // 勝敗判定
        $this->judge($player, $dealer);

        // 終了
        echo 'ブラックジャックを終了します。';
    }

    // 手札の最後の一枚を表示
    public function displayLastHand($name, $hands)
    {
        $lastHand = array_slice($hands, -1, 1);
        echo "{$name}の引いたカードは".$lastHand[0][0].'の'.$lastHand[0][1].'です。'.PHP_EOL;
    }

    // ディーラーの2枚目のカードは表示しない
    public function holeCard()
    {
        echo 'ディーラーの引いた2枚目のカードはわかりません。'.PHP_EOL;
    }

    // プレイヤー:得点が20以下の場合Hit or Stand
    public function playerHitOrStand($player, $deck)
    {
        for ($i = $player->getScore(); $i <= 20; $i = $player->getScore()) {
            $stand = false;
            echo "あなたの現在の得点は{$player->getScore()}です。カードを引きますか？（Y/N）";
            $input = trim(fgets(STDIN));

            switch ($input) {
                case 'Y':
                    $player->addCardAndScore($deck->drawACard());
                    $this->displayLastHand($player->getName(), $player->getHands());
                    break;
                case 'N':
                    $stand = true;
                    break;
                default:
                    break;
            }
            if ($stand === true) {
                break;
            }
        }
    }

    public function openSecondHand($name, $hands)
    {
        $lastHand = array_slice($hands, -1, 1);
        echo "{$name}の引いた2枚目のカードは".$lastHand[0][0].'の'.$lastHand[0][1].'でした。'.PHP_EOL;
    }

    // ディーラー:得点が16以下の場合Hit
    public function dealerHitOrStand($dealer, $deck)
    {
        for ($i = $dealer->getScore(); $i <= 16; $i = $dealer->getScore()) {
            echo "ディーラーの現在の得点は{$dealer->getScore()}です。".PHP_EOL;
            $dealer->addCardAndScore($deck->drawACard());
            $this->displayLastHand($dealer->getName(), $dealer->getHands());
        }
    }

    // 勝敗判定
    public function judge($player, $dealer)
    {
        $playerTotalScore = $player->getScore();
        $dealerTotalScore = $dealer->getScore();
        echo "あなたの得点は{$playerTotalScore}です。".PHP_EOL;
        echo "ディーラーの得点は{$dealerTotalScore}です。".PHP_EOL;

        if ($playerTotalScore > 21) {
            echo 'あなたの負けです。'.PHP_EOL;
        } elseif ($dealerTotalScore > 21) {
            echo 'あなたの勝ちです!'.PHP_EOL;
        } elseif ($playerTotalScore === $dealerTotalScore) {
            echo '引き分けです。'.PHP_EOL;
        } elseif ($playerTotalScore > $dealerTotalScore) {
            echo 'あなたの勝ちです!'.PHP_EOL;
        } elseif ($playerTotalScore < $dealerTotalScore) {
            'あなたの負けです。'.PHP_EOL;
        }
    }
}
$game = new Game();
$game->start();
