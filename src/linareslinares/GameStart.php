<?php

namespace linareslinares;

use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class GameStart {
    public static $ingame = [];
    public static $inpvp = [];

    public static function GameTp(Player $player) : void{
        if(!empty(KFFA::getInstance()->config->getNested("arena.name"))){
            GameStart::ClearInv($player);
            GameStart::TeleportGame($player);
            GameStart::AddBook($player);
            $playing = count(KFFA::getInstance()->getServer()->getWorldManager()->getWorldByName(KFFA::getInstance()->config->getNested("arena.name"))->getPlayers());
            $player->sendTitle(TE::YELLOW. "KIT". TE::BLACK."-". TE::RED. "FFA", TE::GREEN. "ONLINE". TE::BLACK. ": ". TE::YELLOW."[{$playing}]");
            $player->sendMessage(KFFA::getInstance()->prefix.TE::GREEN. "Selecciona tu kit favorito.");
        }
    }

    public static function GameQuit(Player $player) : void{
        GameStart::ClearInv($player);
        $world = KFFA::getInstance()->getServer()->getWorldManager()->getDefaultWorld();
        $player->teleport($world->getSafeSpawn());
        
        $key2 = array_search($player->getName(), GameStart::$inpvp);
        if ($key2 !== false) {
            unset(GameStart::$inpvp[$key2]);
            $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN. "Saliste del minijuego.");
        }
    }

    public static function GameRespawn(Player $player) : void {
        GameStart::ClearInv($player);
        GameStart::TeleportGame($player);
        GameStart::AddBook($player);
        $player->sendTitle(TE::RED. "MORISTE", TE::GREEN. "Vamos de nuevo!");

        $key2 = array_search($player->getName(), GameStart::$inpvp);
        if ($key2 !== false) {
            unset(GameStart::$inpvp[$key2]);
            $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN. "Elige tu kit nuevamente.");
        }
    }

    public static function ClearInv(Player $player) : void{
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getEffects()->clear();
        $player->setScale(1);
        $player->setFlying(false);
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        $player->setGamemode(GameMode::SURVIVAL);
    }

    public static function TeleportGame(Player $player) : void{
        if(!empty(KFFA::getInstance()->config->getNested("arena.name"))) {
            $mapName = KFFA::getInstance()->config->getNested("arena.name");
            $mapCoords = KFFA::getInstance()->config->getNested("arena.coords");
            $world = KFFA::getInstance()->getServer()->getWorldManager()->getWorldByName($mapName);
            if($world !== null){
                $player->teleport($world->getSafeSpawn());
                $coords = explode(" ", $mapCoords);
                $player->teleport(new Vector3((int)$coords[0], (int)$coords[1], (int)$coords[2]));
            } else {
                $player->sendMessage(KFFA::getInstance()->prefix.TE::RED. "El mundo: {$mapName} no existe.");
            }
        }else{
            KFFA::getInstance()->getServer()->getLogger()->warning(TE::RED. "No esta configurada ninguna arena para KIT-FFA.");
        }
    }

    public static function AddBook(Player $player) : void{
        $kselec = VanillaItems::BOOK();
        $kselec->setCustomName(TE::BOLD.TE::BLUE.">> KITS <<");
        $kselec->setCount(1);
        $player->getInventory()->setItem(4, $kselec);
    }

}