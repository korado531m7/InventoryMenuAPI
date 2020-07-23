<?php
namespace korado531m7\InventoryMenuAPI\inventory; 


use pocketmine\block\Block;

class ChestInventory extends MenuInventory{

    public function getName() : string{
        return 'ChestInventory';
    }

    public function getDefaultSize() : int{
        return 27;
    }
    
    public function getNetworkType() : int{
        return self::CONTAINER;
    }

    public function getBlock() : Block{
        return Block::get(Block::CHEST);
    }

}