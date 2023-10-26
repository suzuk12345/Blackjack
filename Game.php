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
        $player->displayLastHand($player->getName(), $player->getHands());
        $player->addCardAndScore($deck->drawACard());
        $player->displayLastHand($player->getName(), $player->getHands());

        // ディーラー:カードを2枚引く&一枚目のみ表示
        $dealer->addCardAndScore($deck->drawACard());
        $dealer->displayLastHand($dealer->getName(), $dealer->getHands());
        $dealer->addCardAndScore($deck->drawACard());
        $dealer->holeCard();

        // プレイヤー:得点が20以下の場合Hit or Stand
        $player->hitOrStand($player, $deck);

        // ディーラー:2枚目のカード公開
        $dealer->openSecondHand($dealer->getName(), $dealer->getHands());

        // ディーラー:得点が16以下の場合Hit
        $dealer->hitOrStand($dealer, $deck);

        // 勝敗判定
        $this->judge($player, $dealer);

        // 終了
        echo 'ブラックジャックを終了します。';
    }

    // 勝敗判定
    public function judge($player, $dealer)
    {
        echo "あなたの得点は{$player->getScore()}です。".PHP_EOL;
        echo "ディーラーの得点は{$dealer->getScore()}です。".PHP_EOL;

        if ($player->getScore() > 21) {
            echo 'あなたの負けです。'.PHP_EOL;
        } elseif ($dealer->getScore() > 21) {
            echo 'あなたの勝ちです!'.PHP_EOL;
        } elseif ($player->getScore() === $dealer->getScore()) {
            echo '引き分けです。'.PHP_EOL;
        } elseif ($player->getScore() > $dealer->getScore()) {
            echo 'あなたの勝ちです!'.PHP_EOL;
        } elseif ($player->getScore() < $dealer->getScore()) {
            echo 'あなたの負けです。'.PHP_EOL;
        }
    }
}
$game = new Game();
$game->start();
