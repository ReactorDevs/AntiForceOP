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

namespace ilai\AntiForceOP;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocektmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase {
    public function onEnable() {
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener($this->getConfig()),
            $this
        );
        // this was disabled due to the fact that its contra-producent
        // $this->getLogger()->info(C::GREEN . "AntiForceOP has been enabled!");
    }

    // Yes i know this is not the best way to do it, but it works and its suffecient for now
    public function onCommand(
        CommandSender $sender,
        Command $command,
        string $label,
        array $args): bool {
        if($command->getName() === "fp"){

            if(!$sender->hasPermission("fop.use")){
                $sender->sendMessage(C::RED . "You don't have permission to use this command!");
                return true;
            }

            switch(strtolower($args[0])){
                case "add":
                        $allowed = $this->getConfig()->get("allowed");
                        array_push($allowed, $args[1]);
                        $this->getConfig()->set("allowed", $allowed);
                        $this->reloadConfig();
                        $sender->sendMessage(C::GREEN . "Action was done sucessfully.");
                        return true;
                break;
                case "help":
                    $sender->sendMessage(C::GREEN . "Usage: /fp <add> <player>");
                    return true;
                break;
            }
        }
    }
}
