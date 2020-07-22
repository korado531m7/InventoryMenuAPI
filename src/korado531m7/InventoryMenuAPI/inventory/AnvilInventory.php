<?php


namespace korado531m7\InventoryMenuAPI\inventory; 


use pocketmine\block\Block;

class AnvilInventory extends MenuInventory{

    public function getDefaultSize() : int{
        return 3;
    }
    
    public function getNetworkType() : int{
        return self::ANVIL;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::ANVIL);
    }

}