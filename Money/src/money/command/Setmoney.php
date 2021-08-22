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

class Setmoney extends PluginCommand
{

    public $plugin;

    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->plugin = $owner;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender->hasPermission("set.money") or !$sender->isOp() and !isset($args[0]) and !isset($args[1])){
            $sender->sendMessage("You dont have permission to run this command");
        } elseif(isset($args[0]) and isset($args[1])) {
            $cek_null = mysqli_fetch_array($this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$args[0]'"));
            if($cek_null !== null){
                $this->plugin->getDatabase()->query("UPDATE money set money=$args[1] WHERE username='$args[0]'");
                $sender->sendMessage("Successfully set money to $args[0] in the amount of $args[1]");
            }
        } else {
           $sender->sendMessage("Usage /setmoney <playername> <amount>");
        }
        return true;
    }
}
