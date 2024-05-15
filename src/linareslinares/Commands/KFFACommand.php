<?php

namespace linareslinares\Commands;

use linareslinares\Entitys\EntityDeaths;
use linareslinares\Entitys\EntityKills;
use linareslinares\GameStart;
use linareslinares\KFFA;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class KFFACommand extends Command {

    public function __construct() {
        parent::__construct("kffa", "<join>/<quit>");
        $this->setPermission("kffa.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) {
            return true;
        }
        if($sender instanceof Player){
            if(empty($args[0])){
                $sender->sendMessage(TE::colorize("§7==========[§eKIT§7-§6FFA§7]==========\n§eCommands help\n§7- Use /kffa join or quit\n§7- Use /kffa ktops\n§7==========[§eKIT§7-§6FFA§7]=========="));
                return true;
            }
            if(!$sender->hasPermission("kffa.command")){
                $sender->sendMessage(KFFA::getInstance()->prefix. TE::RED. "No tienes permiso para usar esto.");
                return true;
            }
            if ($args[0] === "join" && !isset($args[1])) {
                if(!empty(KFFA::getInstance()->config->getNested("arena.name"))){
                    if (in_array($sender->getName(), GameStart::$ingame)) {
                        $sender->sendMessage(KFFA::getInstance()->prefix. TE::RED . "¡Ya estas jugando!");
                        return true;
                    }
                    GameStart::GameTp($sender);
                    GameStart::$ingame[] = $sender->getName();
                }
            }
            if ($args[0] === "quit" && !isset($args[1])) {
                if (!in_array($sender->getName(), GameStart::$ingame)) {
                    $sender->sendMessage(KFFA::getInstance()->prefix. TE::RED . "¡No estás jugando!");
                    return true;
                }
                $key = array_search($sender->getName(), GameStart::$ingame);
                if ($key !== false) {
                    unset(GameStart::$ingame[$key]);
                    GameStart::GameQuit($sender);
                }
            }
            if($args[0] === "ktop" && isset($args[1])) {
                if($args[1] === "kills"){
                    if($sender->hasPermission("setkffa.command")) {
                        $entity = EntityKills::create($sender);
                        $entity->spawnToAll();
                        $sender->sendMessage(KFFA::getInstance()->prefix. TE::GREEN."Top KILLS spawned");
                    }
                }elseif ($args[1] === "deaths"){
                    if($sender->hasPermission("setkffa.command")) {
                        $entity = EntityDeaths::create($sender);
                        $entity->spawnToAll();
                        $sender->sendMessage(KFFA::getInstance()->prefix. TE::GREEN."Top DEATHS spawned");
                    }
                }
            }
        }
        return true;
    }
}