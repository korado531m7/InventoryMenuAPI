<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use pocketmine\block\Block;

class BeaconInventory extends MenuInventory{
    
    public function getDefaultSize() : int{
        return 1;
    }
    
    public function getNetworkType() : int{
        return self::BEACON;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::BEACON);
    }

}