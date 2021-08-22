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

class Seemoney extends PluginCommand
{

    public $plugin;

    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->plugin = $owner;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!isset($args[0])) {
            $sender->sendMessage("Usage /seemoney <playername>");
        } elseif (mysqli_fetch_row($this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$args[0]'")) == null) {
            $sender->sendMessage("Player with name $args[0] not found");
        } else {
            $data = mysqli_fetch_row($this->plugin->getDatabase()->query("SELECT money FROM money WHERE username='$args[0]'"));
            $sender->sendMessage("Money $args[0] is $data[0]");
        }

        return true;
    }
}
