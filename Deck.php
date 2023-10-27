<?php
namespace Blackjack;

require_once 'Card.php';

class Deck
{
    private $deck = [];

    // コンストラクタで暗黙的にデッキを構築
    public function __construct()
    {
        $card = new Card();
        $this->deck = $card->buildDeck();
    }

    // 一枚カードを引く
    public function drawACard()
    {
        $drawnCard = array_pop($this->deck);
        return $drawnCard;
    }

    // デッキの情報を取得
    public function getDeck()
    {
        return $this->deck;
    }
}
