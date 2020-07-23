<?php


namespace korado531m7\InventoryMenuAPI\inventory; 


use pocketmine\block\Block;

class EnchantingTableInventory extends MenuInventory{

    public function getName() : string{
        return 'EnchantingInventory';
    }
    
    public function getDefaultSize() : int{
        return 5;
    }
    
    public function getNetworkType() : int{
        return self::ENCHANTMENT;
    }
    
    public function getBlock() : Block{
        return Block::get(Block::ENCHANTMENT_TABLE);
    }

}