<?php

namespace linareslinares\Entitys;

use linareslinares\KFFA;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TE;

class EntityKills extends Human {

    public $canCollide = false;
    protected float $gravity = 0.0;
    protected $immobile = true;
    protected float $scale = 0.001;
    protected float $drag = 0.0;

    /** @var int|null */

    /**
     * @param Player $player
     *
     * @return EntityKills
     */
    public static function create(Player $player): self {
        $nbt = CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($player->getLocation()->x),
                new DoubleTag($player->getLocation()->y),
                new DoubleTag($player->getLocation()->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($player->getMotion()->x),
                new DoubleTag($player->getMotion()->y),
                new DoubleTag($player->getMotion()->z)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($player->getLocation()->yaw),
                new FloatTag($player->getLocation()->pitch)
            ]));
        return new self($player->getLocation(), $player->getSkin(), $nbt);
    }

    public function canBeMovedByCurrents(): bool {
        return false;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(0.001, 0.001, 0.001);
    }

    /**
     * @param int $currentTick
     *
     * @return bool
     */

    public function onUpdate(int $currentTick): bool{
        $title = TE::colorize("&l&e » &6KIT&8-&cFFA&e « &r");
        $line = TE::colorize(KFFA::getInstance()->config->get("SubTitle_kills"));
        $footer = TE::colorize("&l&7IP&8 › &r&e". KFFA::getInstance()->config->get("IP_server"));
        $msg = $title . TE::EOL . $line . TE::EOL;
        $place = 1;
        $kills = KFFA::getKillsAsRaw();
        arsort($kills);
        foreach($kills as $player => $kill){
            if($place > 10) break;
            $msg .= str_replace(["{place}", "{player}", "{kill}"], [$place, $player, $kill], TE::colorize(KFFA::getInstance()->config->get("Line_topkills"))). TE::EOL;
            $place++;
        }
        $msg .= $footer;
        $this->setNameTag($msg);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTagVisible(true);
        return parent::onUpdate($currentTick);
    }

    /**
     * @param EntityDamageEvent $source
     */

    public function attack(EntityDamageEvent $source): void {
        $source->cancel();

        if (!$source instanceof EntityDamageByEntityEvent) {
            return;
        }

        $damager = $source->getDamager();

        if (!$damager instanceof Player) {
            return;
        }

        if ($damager->getInventory()->getItemInHand()->getTypeId() === VanillaItems::STONE_PICKAXE()->getTypeId()) {
            if ($damager->hasPermission("removetops.command")) {
                $this->kill();
            }
        }
    }
}