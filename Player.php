<?php
class Player
{
    protected $name = 'あなた';
    private $hands = [];
    private $score = 0;

    // プレイヤーの名前を取得
    public function getName()
    {
        return $this->name;
    }

    // プレイヤーの手札を取得
    public function getHands()
    {
        return $this->hands;
    }

    // プレイヤーのスコアを取得
    public function getScore()
    {
        return $this->score;
    }

    // 引いたカード(1枚)を手札に加える&得点を記録する
    public function addCardAndScore($drawnCard)
    {
        $this->hands[] = $drawnCard;
        $this->score += $drawnCard[2];
    }
}
