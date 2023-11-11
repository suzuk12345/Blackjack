<?php
namespace Blackjack;

class Setting
{

    private $numOfCpu = 0;
    private $endFlag = false;

    public function getNumOfCpu()
    {
        return $this->numOfCpu;
    }

    public function getEndFlag()
    {
        return $this->endFlag;
    }

    // // CPUの数を選択(0~2)
    public function setNumberOfCpuPlayer()
    {
        echo 'ブラックジャックを開始します。'.PHP_EOL;
        echo 'CPUの数を選択してください。(0~2)'.PHP_EOL;

        while (true) {
            $this->numOfCpu = trim(fgets(STDIN));

            if (preg_match('/^[0-2]$/', $this->numOfCpu)) {
                $this->numOfCpu = (int)$this->numOfCpu;
                break;
            }

            echo '0~2の内から選択してください。'.PHP_EOL;
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
                    $this->endFlag = true;
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
