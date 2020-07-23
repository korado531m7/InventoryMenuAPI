<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use pocketmine\block\Block;

class HopperInventory extends MenuInventory{

    public function getName() : string{
        return 'HopperInventory';
    }

    public function getDefaultSize() : int{
        return 5;
    }
    
    public function getNetworkType() : int{
        return self::HOPPER;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::HOPPER_BLOCK);
    }

}