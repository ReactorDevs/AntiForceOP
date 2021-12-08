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
use pocketmine\scheduler\Task;
use ilai\AntiForceOP\events\EventListener;

class Main extends PluginBase {

    public function onEnable() {
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener($this->getConfig()),
            $this
        );
	$this->getScheduler()->scheduleRepeatingTask(new class($this) extends Task{

		private $plugin;

		public function __construct(Main $plugin)
		{
			$this->plugin = $plugin;
		}

		public function onRun(int $currentTick = 0)
		{
			foreach($this->plugin->getOnlinePlayers() as $players){
				if(!in_array($players->getName(), $this->plugin->getConfig()->get("allowed")){
					Server::getInstance()->getNameBans()->addBan(
               					$players->getName(),
               					$this->config->get("ban-reason"),
               					null,
               					"AntiForce-OP Detection"
           				);
					$players->kick("AntiForce-OP Detection");
				}
			}
		}
	});
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if($command->getName() === "fp"){
	    if(!isset($args[0]) || trim($args[0]) == ""){
		$sender->sendMessage(C::GREEN . "Usage: /fp <add/remove> <player>");
                return false;
	    }

            if(!$sender->hasPermission("fop.use")){
                $sender->sendMessage(C::RED . "You don't have permission to use this command!");
                return false;
            }

            switch(strtolower($args[0])){
                case "add":
                    if(array_search($args[1], $this->getConfig()->get("allowed"))){
                        $sender->sendMessage(C::RED . "Player already added!");
                        return false;
                    }
                    $allowed = $this->getConfig()->get("allowed");
                    array_push($allowed, $args[1]);
                    $this->getConfig()->set("allowed", $allowed);
                    $this->reloadConfig();
                    $sender->sendMessage(C::GREEN . "Action was done sucessfully.");
                break;
                case "help":
                    $sender->sendMessage(C::GREEN . "Usage: /fp <add/remove> <player>");
                break;
                case "remove":
                    $search = array_search($args[1], $this->getConfig()->get("allowed"));
                    if(!$search){
                        $sender->sendMessage(C::RED . "Player not found!");
                        return false;
                    } else {
                        $allowed = $this->getConfig()->get("allowed");
                        array_splice($allowed, $search, 1);
                        $this->getConfig()->set("allowed", $allowed);
                        $sender->sendMessage(C::GREEN . "Action was done successfully.");
                        return false;
                    }
                break;
            }
        }
    }
}
