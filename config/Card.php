<?php
namespace Blackjack;

class Card
{
    private $suits = ['スペード', 'クラブ', 'ハート', 'ダイヤ'];
    private $score = [
        'A' => 11, // 得点が22以上の場合は1として扱う
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10, // 10以降はすべて10として扱う
        'J' => 10,
        'Q' => 10,
        'K' => 10,
    ];

    // シャッフルしたデッキを構築[['スペード'(柄), 'A'(数字), 1(得点)], ...]
    public function buildDeck()
    {
        $deck = [];
        foreach ($this->suits as $suit) {
            foreach ($this->score as $number => $score) {
                array_push($deck, [$suit, $number, $score]);
            }
        }
        shuffle($deck);
        return $deck;
    }
}
