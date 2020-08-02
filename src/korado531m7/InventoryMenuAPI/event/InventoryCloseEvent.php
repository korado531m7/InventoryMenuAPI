<?php
namespace korado531m7\InventoryMenuAPI\event;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;

use pocketmine\Player;
use pocketmine\event\Cancellable;

class InventoryCloseEvent extends MenuInventoryEvent implements Cancellable{
    protected $who;
    protected $inventory;
    protected $windowId;

    /**
     * @param Player        $who
     * @param MenuInventory $inventory
     * @param int           $windowId
     */
    public function __construct(Player $who, MenuInventory $inventory, int $windowId){
        $this->who = $who;
        $this->inventory = $inventory;
        $this->windowId = $windowId;
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

    /**
     * @return mixed
     */
    public function getWindowId(){
        return $this->windowId;
    }
}
