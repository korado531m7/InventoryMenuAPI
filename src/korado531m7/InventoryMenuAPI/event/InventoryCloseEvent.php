<?php
namespace korado531m7\InventoryMenuAPI\event;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;


use pocketmine\Player;
use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

class InventoryCloseEvent extends PluginEvent implements Cancellable{
    protected $who;
    protected $inventory;
    
    /**
     * @param Player            $who
     * @param MenuInventory $inventory
     */
    public function __construct(Player $who, MenuInventory $inventory){
        $this->who = $who;
        $this->inventory = $inventory;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return MenuInventory
     */
    public function getInventory() : MenuInventory{
        return $this->inventory;
    }
}
