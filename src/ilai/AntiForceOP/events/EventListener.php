<?php

declare(strict_types=1);

namespace ilai\AntiForceOP;

use pocketmine\Server;
use pocketmine\Player;
use pocektmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener {
    public function __construct($config) {
        $this->config = $config;
    }

    public function onPlayerLogin(PlayerLoginEvent $ev){
        $player = $ev->getPlayer();
        $playerName = $player->getName();
        if(!in_array($playerName, $this->config->get("allowed")) && $player->isOp()){
            Server::getInstance()->getNameBans()->addBan(
                $playerName,
                $this->config->get("ban-reason"),
                null,
                "AntiForce-OP Detection"
            );
            $player->kick($this->config->get("ban-message"), false);
        }
    }
}
