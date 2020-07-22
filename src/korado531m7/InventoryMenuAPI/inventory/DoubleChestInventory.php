<?php


namespace korado531m7\InventoryMenuAPI\inventory; 


use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Chest;

class DoubleChestInventory extends ChestInventory{

    public function getDefaultSize() : int{
        return 54;
    }

    public function placeAdditionalBlocks(Player $player, Vector3 $pos) : void{
        InventoryMenuUtils::sendFakeBlock($player, $pos->add(1), $this->getBlock());
        $tag = new CompoundTag();
        $tag->setInt(Chest::TAG_PAIRX, $pos->getFloorX());
        $tag->setInt(Chest::TAG_PAIRZ, $pos->getFloorZ());
        InventoryMenuUtils::sendTagData($player, $tag, $pos->add(1));
    }

    public function breakAdditionalBlocks(Player $player, Vector3 $pos) : void{
        InventoryMenuUtils::removeBlock($player, $pos->add(1));
    }

}