<?php

namespace linareslinares;

use linareslinares\Commands\KFFACommand;
use linareslinares\Commands\SetArenaCommand;
use linareslinares\Entitys\EntityDeaths;
use linareslinares\Entitys\EntityKills;
use linareslinares\Events\KFFAEvent;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat as TE;
use pocketmine\world\World;

class KFFA extends PluginBase {

    public $prefix;
    public Config $config;
    public Config $deathData;
    public Config $killData;

    use SingletonTrait;

    public function onLoad(): void{
        self::setInstance($this);
    }

    public function onEnable() : void {
        $this->getLogger()->info(TE::GREEN . "\n ==========[Cargando KIT-FFA]========= \n==========[By: LinaresFunado]==========");
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->killData = new Config($this->getDataFolder() . "kffakills.yml", Config::YAML);
        $this->deathData = new Config($this->getDataFolder() . "kffadeaths.yml", Config::YAML);
        $this->saveResource("config.yml");
        $this->getServer()->getCommandMap()->register("kffa", new KFFACommand());
        $this->getServer()->getCommandMap()->register("setkffa", new SetArenaCommand());
        $this->getServer()->getPluginManager()->registerEvents(new KFFAEvent(), $this);

        if(!empty($this->config->getNested("arena.name"))) {
            $this->getServer()->getWorldManager()->loadWorld($this->config->getNested("arena.name"));
            $this->getServer()->getLogger()->warning($this->prefix. TE::GREEN. "Se cargo la arena: ". $this->config->getNested("arena.name"). " para KIT-FFA.");
        }else{
            $this->getServer()->getLogger()->warning($this->prefix. TE::RED. "No esta configurada ninguna arena para KIT-FFA.");
        }

        EntityFactory::getInstance()->register(EntityKills::class, function (World $world, CompoundTag $nbt): EntityKills {
            return new EntityKills(EntityDataHelper::parseLocation($nbt, $world), EntityKills::parseSkinNBT($nbt), $nbt);
        }, ["EntityKills"]);

        EntityFactory::getInstance()->register(EntityDeaths::class, function (World $world, CompoundTag $nbt): EntityDeaths {
            return new EntityDeaths(EntityDataHelper::parseLocation($nbt, $world), EntityDeaths::parseSkinNBT($nbt), $nbt);
        }, ["EntityDeaths"]);

        $this->prefix = TE::DARK_GRAY."[".TE::YELLOW."KIT".TE::DARK_GRAY."-".TE::GOLD."FFA".TE::DARK_GRAY."] ";
    }

    public static function getInstance(): KFFA{
        return self::$instance;
    }

    public static function getKillsAsRaw(): array{
        $data = [];
        foreach (self::getInstance()->killData->getAll() as $player => $kills){
            $data[$player] = $kills;
        }
        return $data;
    }

    public static function getKills(string $player): int{
        return intval(self::getInstance()->killData->get($player, 0));
    }

    public static function addKills(string $player): void{
        self::getInstance()->killData->set($player, self::getKills($player) + 1);
        self::getInstance()->killData->save();
    }

    public static function getDeathsAsRaw(): array{
        $data = [];
        foreach (self::getInstance()->deathData->getAll() as $player => $deaths){
            $data[$player] = $deaths;
        }
        return $data;
    }

    public static function getDeaths(string $player): int{
        return intval(self::getInstance()->deathData->get($player, 0));
    }

    public static function addDeaths(string $player): void{
        self::getInstance()->deathData->set($player, self::getDeaths($player) + 1);
        self::getInstance()->deathData->save();
    }

    public static function PlaySound(Player $player, string $sound, int $volume, float $pitch){
        $packet = new PlaySoundPacket();
        $packet->x = $player->getPosition()->getX();
        $packet->y = $player->getPosition()->getY();
        $packet->z = $player->getPosition()->getZ();
        $packet->soundName = $sound;
        $packet->volume = $volume;
        $packet->pitch = $pitch;
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}