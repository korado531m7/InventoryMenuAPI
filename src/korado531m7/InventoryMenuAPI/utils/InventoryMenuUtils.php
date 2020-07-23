<?php
namespace korado531m7\InventoryMenuAPI\utils;


use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class InventoryMenuUtils{

    public static function sendTagData(Player $player, CompoundTag $tag, Vector3 $pos){
        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockActorDataPacket;
        $pk->x = (int) $pos->x;
        $pk->y = (int) $pos->y;
        $pk->z = (int) $pos->z;
        $pk->namedtag = $writer->write($tag);
        $player->dataPacket($pk);
    }
    
    public static function sendFakeBlock(Session $session, Vector3 $pos, Block $block){
        $session->addBlock($session->getPlayer()->getLevel()->getBlock($pos));

        $pk = new UpdateBlockPacket();
        $pk->x = (int) $pos->x;
        $pk->y = (int) $pos->y;
        $pk->z = (int) $pos->z;
        $pk->flags = UpdateBlockPacket::FLAG_ALL;
        $pk->blockRuntimeId = $block->getRuntimeId();
        $session->getPlayer()->dataPacket($pk);
    }

}