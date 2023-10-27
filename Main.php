<?php
namespace Blackjack;

require_once 'Game.php';

$game = new Game();
$game->setNumberOfCpuPlayer();
$game->createCpuPlayer($game->getNamberOfCpu());
$game->start();
