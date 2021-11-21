<?php

declare(strict_types=1);

namespace ilai\AntiForceOP;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocektmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\Listener;

class Main extends PluginBase {
    public function onEnable() {
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener($this->getConfig()),
            $this
        );
        $this->getLogger()->info(C::GREEN . "AntiForceOP has been enabled!");
    }
}
