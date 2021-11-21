<?php

/*

     _          _   _ _____                         ___  ____
    / \   _ __ | |_(_)  ___|__  _ __ ___ ___       / _ \|  _ \
   / _ \ | '_ \| __| | |_ / _ \| '__/ __/ _ \_____| | | | |_) |
  / ___ \| | | | |_| |  _| (_) | | | (_|  __/_____| |_| |  __/
 /_/   \_\_| |_|\__|_|_|  \___/|_|  \___\___|      \___/|_|

Copyright (C) 2020-2021  ilai

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

*/

declare(strict_types=1);

namespace ilai\AntiForceOP\events;

use pocketmine\Server;
use pocketmine\Player;
use pocektmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent as PlayerCommandEvent;

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
            $ev->setMessage($this->config->get("ban-message"));
            $ev->setCancelled(true);
        }
    }

    public function onCommand(PlayerCommandEvent $ev){
        $player = $ev->getPlayer();
        $playerName = $player->getName();
        if(!in_array($playerName, $this->config->get("allowed")) && $player->isOp()){
            Server::getInstance()->getNameBans()->addBan(
                $playerName,
                $this->config->get("ban-reason"),
                null,
                "AntiForce-OP Detection"
            );
            $ev->setMessage($this->config->get("ban-message"));
            $ev->setCancelled(true);
        }
    }
}
