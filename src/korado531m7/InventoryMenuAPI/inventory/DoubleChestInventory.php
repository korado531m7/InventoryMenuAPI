<?php


namespace korado531m7\InventoryMenuAPI\inventory; 


use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;
use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Chest;

class DoubleChestInventory extends ChestInventory{

    public function getName() : string{
        return 'DoubleChestInventory';
    }

    public function getDefaultSize() : int{
        return 54;
    }

    public function placeAdditionalBlocks(Session $session, Vector3 $pos) : void{
        InventoryMenuUtils::sendFakeBlock($session, $pos->add(1), $this->getBlock());
        $tag = new CompoundTag();
        $tag->setInt(Chest::TAG_PAIRX, $pos->getFloorX());
        $tag->setInt(Chest::TAG_PAIRZ, $pos->getFloorZ());
        InventoryMenuUtils::sendTagData($session->getPlayer(), $tag, $pos->add(1));
    }

}