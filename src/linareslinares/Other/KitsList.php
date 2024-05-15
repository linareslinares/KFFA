<?php

namespace linareslinares\Other;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class KitsList {

    public static function ironKit(Player $player) : void{
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $sword = VanillaItems::IRON_SWORD()->setCustomName(TE::BOLD.TE::BLUE."GUERRERO")->setCount(1);
        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1));

        $helmet = VanillaItems::DIAMOND_HELMET()->setCustomName(TE::BOLD.TE::BLUE."GUERRERO")->setCount(1);
        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
        $chest = VanillaItems::IRON_CHESTPLATE()->setCustomName(TE::BOLD.TE::BLUE."GUERRERO")->setCount(1);
        $chest->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
        $leggin = VanillaItems::IRON_LEGGINGS()->setCustomName(TE::BOLD.TE::BLUE."GUERRERO")->setCount(1);
        $leggin->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
        $boots = VanillaItems::DIAMOND_BOOTS()->setCustomName(TE::BOLD.TE::BLUE."GUERRERO")->setCount(1);
        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
        $apple = VanillaItems::GOLDEN_APPLE()->setCustomName(TE::BOLD.TE::GOLD."APPLE")->setCount(1);
        $player->getInventory()->addItem($apple);

        $player->getInventory()->setItem(0, $sword);
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chest);
        $player->getArmorInventory()->setLeggings($leggin);
        $player->getArmorInventory()->setBoots($boots);
    }

    public static function potKit(Player $player) : void{
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $sword = VanillaItems::STONE_SWORD()->setCustomName(TE::BOLD.TE::BLUE."CENTELLA")->setCount(1);
        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 1));
        $pot = VanillaItems::SPLASH_POTION()->setType(PotionType::STRONG_HEALING);
        $pot->setCustomName(TE::BOLD.TE::BLUE. "CENTELLA")->setCount(1);
        $helmet = VanillaItems::IRON_HELMET()->setCustomName(TE::BOLD.TE::BLUE."CENTELLA")->setCount(1);
        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
        $chest = VanillaItems::GOLDEN_CHESTPLATE()->setCustomName(TE::BOLD.TE::BLUE."CENTELLA")->setCount(1);
        $chest->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
        $leggin = VanillaItems::GOLDEN_LEGGINGS()->setCustomName(TE::BOLD.TE::BLUE."CENTELLA")->setCount(1);
        $leggin->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3));
        $boots = VanillaItems::IRON_BOOTS()->setCustomName(TE::BOLD.TE::BLUE."CENTELLA")->setCount(1);
        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));

        for ($i = 0; $i < 9; $i++){
            $player->getInventory()->addItem($pot);
        }
        $player->getInventory()->setItem(0, $sword);
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chest);
        $player->getArmorInventory()->setLeggings($leggin);
        $player->getArmorInventory()->setBoots($boots);
    }

    public static function asesinoKit(Player $player) : void{
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $sword = VanillaItems::DIAMOND_SWORD()->setCustomName(TE::BOLD.TE::BLUE."ASESINO")->setCount(1);
        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2));
        $helmet = VanillaItems::IRON_BOOTS()->setCustomName(TE::BOLD.TE::BLUE."ASESINO")->setCount(1);
        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
        $chest = VanillaItems::CHAINMAIL_LEGGINGS()->setCustomName(TE::BOLD.TE::BLUE."ASESINO")->setCount(1);
        $chest->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
        $apple = VanillaItems::GOLDEN_APPLE()->setCustomName(TE::BOLD.TE::GOLD."APPLE")->setCount(1);
        $player->getInventory()->addItem($apple);
        $player->getInventory()->setItem(0, $sword);
        $player->getArmorInventory()->setBoots($helmet);
        $player->getArmorInventory()->setLeggings($chest);
    }

    public static function arqueroKit(Player $player) : void{
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $sword = VanillaItems::GOLDEN_SWORD()->setCustomName(TE::BOLD.TE::BLUE."ARQUERO")->setCount(1);
        $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 1));
        $bow = VanillaItems::BOW()->setCustomName(TE::BOLD.TE::BLUE."ARQUERO")->setCount(1);
        $bow->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH(), 1));
        $arrow = VanillaItems::ARROW()->setCustomName(TE::BOLD.TE::BLUE."ARQUERO")->setCount(16);
        $helmet = VanillaItems::CHAINMAIL_HELMET()->setCustomName(TE::BOLD.TE::BLUE."ARQUERO")->setCount(1);
        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
        $chest = VanillaItems::DIAMOND_CHESTPLATE()->setCustomName(TE::BOLD.TE::BLUE."ARQUERO")->setCount(1);
        $chest->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1));
        $apple = VanillaItems::GOLDEN_APPLE()->setCustomName(TE::BOLD.TE::GOLD."APPLE")->setCount(1);
        $player->getInventory()->addItem($apple);
        $player->getInventory()->setItem(0, $sword);
        $player->getInventory()->setItem(1, $bow);
        $player->getInventory()->setItem(20, $arrow);
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chest);
    }

}