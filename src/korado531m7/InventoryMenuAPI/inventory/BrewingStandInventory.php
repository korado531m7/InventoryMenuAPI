<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use pocketmine\block\Block;

class BrewingStandInventory extends MenuInventory{

    public function getName() : string{
        return 'BrewingStandInventory';
    }
    
    public function getDefaultSize() : int{
        return 5;
    }
    
    public function getNetworkType() : int{
        return self::BREWING_STAND;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::BREWING_STAND_BLOCK);
    }

}