<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use pocketmine\block\Block;

class DropperInventory extends MenuInventory{

    public function getDefaultSize() : int{
        return 9;
    }
    
    public function getNetworkType() : int{
        return self::DROPPER;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::DROPPER);
    }

}