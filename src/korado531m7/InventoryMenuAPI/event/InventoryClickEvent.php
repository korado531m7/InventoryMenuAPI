<?php


namespace korado531m7\InventoryMenuAPI\event;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;

use pocketmine\event\Cancellable;
use pocketmine\Player;
use pocketmine\item\Item;

class InventoryClickEvent extends MenuInventoryEvent implements Cancellable{
    protected $who;
    protected $item;
    protected $inventory;
    
    /**
     * @param Player                     $who
     * @param Item                       $item
     * @param MenuInventory              $inventory
     */
    public function __construct(Player $who, Item $item, MenuInventory $inventory){
        $this->who = $who;
        $this->item = $item;
        $this->inventory = $inventory;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->who;
    }
    
    /**
     * @return Item
     */
    public function getItem() : Item{
        return $this->item;
    }

    /**
     * @return MenuInventory
     */
    public function getInventory() : MenuInventory{
        return $this->inventory;
    }
}