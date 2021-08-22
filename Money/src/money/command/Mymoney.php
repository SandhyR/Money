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

class Mymoney extends PluginCommand
{

    public $plugin;

    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->plugin = $owner;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            $name = $sender->getName();
            $money = $this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$name'");
            $money = mysqli_fetch_array($money);
            $sender->sendMessage("Your money: $money[0]");
        } else {
            $sender->sendMessage("You not a player!");
        }
        return true;
    }
}
