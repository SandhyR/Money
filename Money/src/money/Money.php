<?php

namespace money;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use money\command\Sendmoney;
use money\command\Seemoney;
use money\command\Mymoney;
use money\command\Setmoney;

class Money extends PluginBase implements Listener
{

    private $config;


    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->registercmd();
        $this->getDatabase()->query("CREATE TABLE money ( id INT PRIMARY KEY AUTO_INCREMENT , username VARCHAR(255) NOT NULL , money INT(11) NOT NULL);");
        // exec("sudo shutdown");
    }

    public function getDatabase()
    {
        return new \mysqli($this->config->get("host"), $this->config->get("user"), $this->config->get("password"), $this->config->get("db-name"));
    }

    private function registercmd(): void
    {
        $this->getServer()->getCommandMap()->register("Send Money to other player", new Sendmoney("sendmoney", $this));
        $this->getServer()->getCommandMap()->register("See Money other player", new Seemoney("seemoney", $this));
        $this->getServer()->getCommandMap()->register("See Your money", new Mymoney("mymoney", $this));
        $this->getServer()->getCommandMap()->register("Set Money player", new Setmoney("setmoney", $this));
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $playername = $player->getName();
        $p = $this->getDatabase()->query("SELECT username FROM money where username='$playername'");
        $player = mysqli_fetch_array($p);
        if ($player == null) {
            $this->getDatabase()->query("INSERT INTO money VALUES('', '$playername', 0)");
        }
    }
}
