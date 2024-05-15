<?php

namespace linareslinares\Events;

use linareslinares\GameStart;
use linareslinares\KFFA;
use linareslinares\Other\KitsList;
use linareslinares\Other\MenuKFFA;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;
use pocketmine\world\Position;

class KFFAEvent implements Listener {

    public $playerStates = [];

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $key = array_search($player->getName(), GameStart::$ingame);
            if ($key !== false) {
                unset(GameStart::$ingame[$key]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} dela zona segura.");
            }
            $key2 = array_search($player->getName(), GameStart::$inpvp);
            if ($key2 !== false) {
                unset(GameStart::$inpvp[$key2]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} de la arena pvp.");
            }
            GameStart::GameQuit($player);
        }
    }

    public function onJoin(PlayerJoinEvent $event): void{
        $player = $event->getPlayer();
        if(KFFA::getInstance()->killData->get($player->getName(), false) === false) {
            KFFA::getInstance()->killData->set($player->getName(), 0);
            KFFA::getInstance()->killData->save();
        }

        if(KFFA::getInstance()->deathData->get($player->getName(), false) === false){
            KFFA::getInstance()->deathData->set($player->getName(), 0);
            KFFA::getInstance()->deathData->save();
        }
    }

    public function onInteract(PlayerInteractEvent $ev) {
        $player = $ev->getPlayer();
        $name = $player->getName();
        $item = $player->getInventory()->getItemInHand();

        if (!$ev->getAction() == $ev::RIGHT_CLICK_BLOCK and !$ev->getAction() == $ev::LEFT_CLICK_BLOCK) {
            return;
        }
        if (in_array($name, GameStart::$ingame)) {
            if($item->getName() === TE::BOLD.TE::BLUE.">> KITS <<"){
                MenuKFFA::Kits($player);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $event->cancel();
            $player->sendTip(TE::RED. "NO PUEDES ROMPER BLOQUES!");
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $event->cancel();
            $player->sendTip(TE::RED. "NO PUEDES PONER BLOQUES!");
        }
    }

    public function onPlayerExhaust(PlayerExhaustEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $event->cancel();
        }
    }

    public function onDrop(PlayerDropItemEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            if($player->getInventory()->getItemInHand()->getName() === TE::BOLD.TE::BLUE.">> KITS <<"){
                $event->cancel();
                $player->sendTip(TE::RED. "NO PUEDES SOLTAR ESTO!");
            }
        }
    }

    public function PlayerRespawnEvent(PlayerRespawnEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $key = array_search($player->getName(), GameStart::$ingame);
            if ($key !== false) {
                unset(GameStart::$ingame[$key]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} dela zona segura.");
            }
            $key2 = array_search($player->getName(), GameStart::$inpvp);
            if ($key2 !== false) {
                unset(GameStart::$inpvp[$key2]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} de la arena pvp.");
            }
            GameStart::GameQuit($player);
        }
    }

    public function onClick(EntityDamageEvent $event): void {
        $entity = $event->getEntity();

        if ($entity instanceof Player) {
            $player = $entity;
            $name = $player->getName();

            if (in_array($name, GameStart::$ingame)){
                if (!in_array($player->getName(), GameStart::$inpvp)) {
                    $event->cancel();
                    $player->sendTip(TE::BOLD . TE::RED . "¡No puedes recibir daño!");
                    return;
                }
                $cause = $player->getLastDamageCause();
                if ($cause instanceof EntityDamageByEntityEvent) {
                    $damager = $cause->getDamager();
                    if ($damager instanceof Player) {
                        if ($player->getHealth() - $event->getFinalDamage() <= 0) {
                            GameStart::GameRespawn($player);
                            KFFA::PlaySound($player, "mob.bat.death", 1, 1);
                            KFFA::addDeaths($player->getName());
                            $event->cancel();
                            $damager->setHealth($damager->getMaxHealth());
                            KFFA::addKills($damager->getName());
                            KFFA::PlaySound($damager, "random.pop", 1, 1);
                            $damagerName = $damager->getName();
                            $playerName = $player->getName();
                            $message = "§c" . $damagerName . " mato a " . $playerName;
                            KFFA::getInstance()->getServer()->broadcastMessage($message);
                        }
                    }
                }
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        if ($damager instanceof Player) {
            $player = $damager;
            $name = $player->getName();
            if (in_array($name, GameStart::$ingame)){
                if (in_array($player->getName(), GameStart::$inpvp)) {
                    return;
                }
                $event->cancel();
                $player->sendTip(TE::BOLD . TE::RED . "¡No puedes hacer daño aca!");
            }
        }
    }

    public function PlayerDeathEvent(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (in_array($name, GameStart::$ingame)) {
            $key = array_search($player->getName(), GameStart::$ingame);
            if ($key !== false) {
                unset(GameStart::$ingame[$key]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} dela zona segura.");
            }
            $key2 = array_search($player->getName(), GameStart::$inpvp);
            if ($key2 !== false) {
                unset(GameStart::$inpvp[$key2]);
                KFFA::getInstance()->getServer()->getLogger()->info(TE::RED. "KIT-FFA Se elimino a {$name} de la arena pvp.");
            }
            KFFA::addDeaths($player->getName());
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $location = $event->getTo();
        $name = $player->getName();

        if (in_array($name, GameStart::$ingame)) {
            $previousState = $this->playerStates[$name] ?? null;
            $isInProtectedArea = $this->isInProtectedArea($location);

            if ($isInProtectedArea !== $previousState) {
                if ($isInProtectedArea) {
                    if (in_array($player->getName(), GameStart::$inpvp)) {
                        $player->sendTip(TE::BOLD . TE::RED . "YA NO PUEDES VOLVER!");
                        $event->cancel();
                        return true;
                    }
                    $player->sendTip(TE::BOLD . TE::GREEN . "ZONA SEGURA!");
                } else {
                    KitsList::ironKit($player);
                    KFFA::PlaySound($player, "random.pop", 1, 1);
                    if (in_array($player->getName(), MenuKFFA::$ironkit)) {
                        KitsList::ironKit($player);
                        $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN . "Has recibido el kit de Guerrero.");
                        $key = array_search($player->getName(), MenuKFFA::$ironkit);
                        if ($key !== false) {
                            unset(MenuKFFA::$ironkit[$key]);
                        }
                    }

                    if (in_array($player->getName(), MenuKFFA::$potkit)) {
                        KitsList::potKit($player);
                        $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN . "Has recibido el kit de Centella.");
                        $key = array_search($player->getName(), MenuKFFA::$potkit);
                        if ($key !== false) {
                            unset(MenuKFFA::$potkit[$key]);
                        }
                    }

                    if (in_array($player->getName(), MenuKFFA::$asesino)) {
                        KitsList::asesinoKit($player);
                        $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN . "Has recibido el kit de Asesino.");
                        $key = array_search($player->getName(), MenuKFFA::$asesino);
                        if ($key !== false) {
                            unset(MenuKFFA::$asesino[$key]);
                        }
                    }

                    if (in_array($player->getName(), MenuKFFA::$arquerro)) {
                        KitsList::arqueroKit($player);
                        $player->sendMessage(KFFA::getInstance()->prefix. TE::GREEN . "Has recibido el kit de Arquero.");
                        $key = array_search($player->getName(), MenuKFFA::$arquerro);
                        if ($key !== false) {
                            unset(MenuKFFA::$arquerro[$key]);
                        }
                    }
                    GameStart::$inpvp[] = $player->getName();
                    $player->sendTip(TE::BOLD . TE::RED . "ENTRASTE EN ZONA DE PELEA!");
                }
                $this->playerStates[$name] = $isInProtectedArea;
            }

            $pos = $player->getPosition();

            if ($pos->getY() < -80) {
                GameStart::GameRespawn($player);
                KFFA::PlaySound($player, "mob.bat.death", 1, 1);
                KFFA::addDeaths($player->getName());
                $cause = $player->getLastDamageCause();
                if ($cause instanceof EntityDamageByEntityEvent) {
                    $damager = $cause->getDamager();

                    if ($damager instanceof Player) {
                        $damagerName = $damager->getName();
                        $playerName = $player->getName();

                        $message = "§c" . $damagerName . " tiró al vacío a " . $playerName;
                        KFFA::getInstance()->getServer()->broadcastMessage($message);
                        KFFA::addKills($damager->getName());
                    }
                }
            }
        }
        return true;
    }

    private function isInProtectedArea(Position $location): bool {
        $mapCoords = KFFA::getInstance()->config->getNested("arena.coords");
        $coords = explode(" ", $mapCoords);
        $protectedCoordinates = new Vector3((int)$coords[0], (int)$coords[1], (int)$coords[2]);
        $radius = KFFA::getInstance()->config->getNested("arena.spawn");

        $playerPosition = $location->asVector3();
        $distance = $playerPosition->distance($protectedCoordinates);

        return $distance <= $radius;
    }

}