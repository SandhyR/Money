<?php

declare(strict_types=1);

namespace money\command;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class Sendmoney extends PluginCommand
{

    private $plugin;

    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->plugin = $owner;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $name = $sender->getName();
        if ($sender instanceof Player and isset($args[0]) and isset($args[1]) and mysqli_fetch_row($this->plugin->getDatabase()->query("SELECT username FROM money where username='$args[0]'")) !== null and $args[1] <= mysqli_fetch_array($this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$name'"))) {
            $target = $args[0];
            $money = $args[1];
            $name = $sender->getName();
            $a = $this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$name'");
            $getmoney = mysqli_fetch_array($a);
            $this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$target'");
            $targetmoney = $this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$target'");
            $moneytarget = mysqli_fetch_array($targetmoney);
            if ($getmoney[0] < $args[1]) {
                $sender->sendMessage("Your money not enought");
            } elseif ($this->plugin->getServer()->getPlayer($args[0]) !== null) {
                $this->plugin->getDatabase()->query("UPDATE money SET money=$moneytarget[0] + $money WHERE username='$target'");
                $this->plugin->getDatabase()->query("UPDATE money SET money=$getmoney[0] - $money WHERE username='$name'");
                $cek_null = mysqli_fetch_row($this->plugin->getDatabase()->query("SELECT username FROM money where username='$args[0]'"));
                $sender->sendMessage("Successfully sent money to $args[0] in the amount of $args[1]");
                $player = $this->plugin->getServer()->getPlayer($args[0]);
                $player->sendMessage("You got money from $name amount $args[1]");
            } else {
                $this->plugin->getDatabase()->query("UPDATE money SET money=$moneytarget[0] + $money WHERE username='$target'");
                $this->plugin->getDatabase()->query("UPDATE money SET money=$getmoney[0] - $money WHERE username='$name'");
                $cek_null = mysqli_fetch_row($this->plugin->getDatabase()->query("SELECT username FROM money where username='$args[0]'"));
                $sender->sendMessage("Successfully sent money to $args[0] in the amount of $args[1]");
            }
        } elseif (!isset($args[0]) and !isset($args[1])) {
            $sender->sendMessage("Usage /sendmoney <playername> <amount>");
        } elseif (mysqli_fetch_row($cek_null[0] = $this->plugin->getDatabase()->query("SELECT username FROM money where username='$args[0]'")) == null) {
            $sender->sendMessage("Player with name $args[0] not found");
        } else {
            $sender->sendMessage("Usage /sendmoney <playername> <amount>");
            return true;
        }
    }
}


