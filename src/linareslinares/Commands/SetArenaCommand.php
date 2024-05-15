<?php

namespace linareslinares\Commands;

use linareslinares\KFFA;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class SetArenaCommand extends Command {
    public function __construct() {
        parent::__construct("setkffa", "{mapName} {spawnCoords}");
        $this->setPermission("setkffa.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) {
            return true;
        }
        if($sender instanceof Player) {
            if(!$sender->hasPermission("setkffa.command")) {
                $sender->sendMessage(KFFA::getInstance()->prefix. TE::RED . "No tienes permisos para usar este comando.");
                return true;
            }
            if(empty($args[0])){
                $sender->sendMessage(KFFA::getInstance()->prefix.TE::RED. "Ingresa el nombre del mapa.");
                return true;
            }
            if(empty($args[1])){
                $sender->sendMessage(KFFA::getInstance()->prefix.TE::RED. "Ingresa las coordenadas del spawn.");
                return true;
            }
            if (!preg_match('/^[\d\s-]+$/', $args[1])) {
                $sender->sendMessage(KFFA::getInstance()->prefix.TE::RED . "Por favor, ingresa coordenadas válidas en el formato 'x y z'.");
                return true;
            }

            $mapName = $args[0];
            $mapCoords = implode(" ", array_slice($args, 1));
            KFFA::getInstance()->getServer()->getWorldManager()->loadWorld($mapName);
            $world = KFFA::getInstance()->getServer()->getWorldManager()->getWorldByName($mapName);
            if($world !== null){
                KFFA::getInstance()->config->setNested("arena.name", $mapName);
                KFFA::getInstance()->config->setNested("arena.coords", $mapCoords);
                KFFA::getInstance()->config->save();
                $sender->sendMessage(TE::colorize("§7==========[§eKIT§7-§6FFA§7]==========\n§eCreaste la arena exitosamente.\n§6Mapa§7:§e {$mapName}\n§6Coords§7:§e {$mapCoords}\n§6Estado§7:§e activo\n§7==========[§eKIT§7-§6FFA§7]=========="));
                $sender->teleport($world->getSafeSpawn());
                $coords = explode(" ", $mapCoords);
                $sender->teleport(new Vector3((int)$coords[0], (int)$coords[1], (int)$coords[2]));
            } else {
                $sender->sendMessage(KFFA::getInstance()->prefix.TE::RED. "El mundo: {$mapName} no existe.");
            }
        }
        return true;
    }

}