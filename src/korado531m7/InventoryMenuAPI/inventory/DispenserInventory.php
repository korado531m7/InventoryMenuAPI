<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use pocketmine\block\Block;

class DispenserInventory extends MenuInventory{

    public function getName() : string{
        return 'DispenserInventory';
    }
    
    public function getDefaultSize() : int{
        return 9;
    }
    
    public function getNetworkType() : int{
        return self::DISPENSER;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::DISPENSER);
    }

}