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
    /** @var int|null */
    private $eid;

    public function __construct(Player $player, ?int $eid){
        $this->player = $player;
        $this->pos = $player->floor()->subtract(0, 4);
        $this->eid = $eid;
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
     * @return int
     */
    public function getEid() : ?int{
        return $this->eid;
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
            $pk->x = $block->getX();
            $pk->y = $block->getY();
            $pk->z = $block->getZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $this->player->dataPacket($pk);
        }
    }
}