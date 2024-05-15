<?php

namespace linareslinares\Other;

use linareslinares\KFFA;
use linareslinares\utils\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class MenuKFFA {

    public static $ironkit = [];
    public static $potkit = [];
    public static $asesino = [];
    public static $arquerro = [];

    public static function Kits($player) {
        $form = new SimpleForm(function(Player $player, int $data = null){
            if ($data === null) {
                return true;
            }

            $msg = KFFA::getInstance()->prefix. TE::GREEN."Has seleccionado tu kit, vamos a la batalla!";
            switch ($data) {
                case 0:
                    MenuKFFA::$ironkit[] = $player->getName();
                    $player->getInventory()->clearAll();
                    $player->sendMessage($msg);
                    break;
                case 1:
                    MenuKFFA::$potkit[] = $player->getName();
                    $player->getInventory()->clearAll();
                    $player->sendMessage($msg);
                    break;
                case 2:
                    MenuKFFA::$asesino[] = $player->getName();
                    $player->getInventory()->clearAll();
                    $player->sendMessage($msg);
                    break;
                case 3:
                    MenuKFFA::$arquerro[] = $player->getName();
                    $player->getInventory()->clearAll();
                    $player->sendMessage($msg);
                    break;
            }
        });
        $form->setTitle(TE::colorize("&l&bKITS"));
        $form->addButton(TE::colorize("&l&aGuerrero\n&r&eClick"), 0, "textures/ui/icon_recipe_equipment");
        $form->addButton(TE::colorize("&l&aCentella\n&r&eClick"), 0, "textures/ui/icon_recipe_equipment");
        $form->addButton(TE::colorize("&l&aAsesino\n&r&eClick"), 0, "textures/ui/icon_recipe_equipment");
        $form->addButton(TE::colorize("&l&aArquero\n&r&eClick"), 0, "textures/ui/icon_recipe_equipment");
        $form->sendToPlayer($player);
        return $form;
    }
}