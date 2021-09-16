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
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this,  $this);
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->getLogger()->info("AntiForceOP plugin loaded.");
        if($this->getConfig()->get("disableInitialWarning") !== true && $this->getConfig()->get("enable") == true) {
            $this->getLogger()->warning("Every player who haves OP and hasn't been registered on the configuration file will be kicked!");
        }
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        // The player cannot execute comamnd if isn't registered:
        if($this->getConfig()->get("enable")) {
            $player = $sender->getPlayer();
            $playerName = $player->getName();
            $allowedPlayers = $this->getConfig()->get("allowed", []);
            if(!in_array($playerName, $allowedPlayers)){
            $ev->setKickMessage($this->getConfig()->get("kickMessage"));
            $ev->setCancelled(true);
            $this->getLogger()->notice("AntiForceOP has detected a unregistered OP: " . $playerName . "!");
          }
        }
        // Now, the commands of the plugin
        switch (strtolower($command->getName())) {
            case 'forceop':
                if(!$sender instanceof Player){
                    $sender->sendMessage("You cannot disable protection as console.");
                    return false;
                } else {
                    if(!isset($args[0])){
                        $sender->sendMessage(C::RED . "Type: /forceop disable/enable");
                    } else {
                        switch (strtolower($args[0])) {
                            case 'disable':
                                if ($sender->hasPermission("antiforceop.commands.manage")) {
                                    $sender->sendMessage(C::GREEN . "Anti-ForceOP protection has been disabled.");
                                } else {
                                    $sender->sendMessage(C::RED . "You don't have the required permissions.");
                                    return false;
                                }
                                break;
                            case "enable":
                            if ($sender->hasPermission("antiforceop.commands.manage")) {
                                $sender->sendMessage(C::GREEN . "Anti-ForceOP protection has been enabled.");
                            } else {
                                $sender->sendMessage(C::RED . "You don't have the required permissions");
                            }
                                break;
                        }
                    }
                }
                break;
        }
    }
    public function onLogin(PlayerLoginEvent $ev){
        if(!$this->getConfig->get("enable")) return;
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
