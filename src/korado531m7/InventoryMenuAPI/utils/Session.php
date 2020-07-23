<?php


namespace korado531m7\InventoryMenuAPI\utils;


use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\Player;

class Session{
    /** @var Player */
    private $player;
    /** @var Vector3 */
    private $pos;
    /** @var Block[] */
    private $blocks = [];

    public function __construct(Player $player){
        $this->player = $player;
        $this->pos = $player->add(0, 4);
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player{
        return $this->player;
    }

    /**
     * @return Vector3
     */
    public function getPosition() : Vector3{
        return $this->pos;
    }

    /**
     * @param Block $block
     */
    public function addBlock(Block $block) : void{
        $this->blocks[] = $block;
    }

    /**
     * @return Block[]
     */
    public function getBlocks() : array{
        return $this->blocks;
    }

    public function restoreBlock() : void{
        foreach($this->blocks as $block){
            $pk = new UpdateBlockPacket();
            $pk->x = $block->getFloorX();
            $pk->y = $block->getFloorY();
            $pk->z = $block->getFloorZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $this->player->dataPacket($pk);
        }
    }
}