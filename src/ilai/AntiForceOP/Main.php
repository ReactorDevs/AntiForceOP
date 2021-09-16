<?php

declare(strict_types=1);

namespace ilai\AntiForceOP;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocektmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class Main extends PluginBase implements Listener {

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this,  $this);
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->getLogger()->info("AntiForceOP loaded.");
        if($this->getConfig()->get("disableInitialWarning") !== true) {
            $this->getLogger()->warning("Every player who haves OP and hasn't been registered on the configuration file will be kicked!");
        }
    }

    public function onLogin(PlayerLoginEvent $ev){
        $player = $ev->getPlayer();
        $playerName = $player->getName();
        $allowedPlayers = $this->getConfig()->get("allowed", []);
        if(!in_array($playerName, $allowedPlayers)){
            $ev->setKickMessage($this->getConfig()->get("kickMessage"));
            $ev->setCancelled(true);
            $this->getLogger()->notice("AntiForceOP has detected a unregistered OP: " . $playerName . "!");
        }
    }
}
