<?php


namespace korado531m7\InventoryMenuAPI\task;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\tile\Nameable;

class SendInventoryTask extends Task{
    /** @var Player */
    private $player;
    /** @var MenuInventory */
    private $inventory;
    /** @var Vector3 */
    private $pos;

    public function __construct(MenuInventory $inventory, Player $player, Vector3 $pos){
        $this->player = $player;
        $this->inventory = $inventory;
        $this->pos = $pos;
    }

    public function onRun(int $currentTick){
        $tags = new CompoundTag();
        $tags->setString(Nameable::TAG_CUSTOM_NAME, $this->inventory->getName());

        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockActorDataPacket();
        $pk->x = $this->pos->getFloorX();
        $pk->y = $this->pos->getFloorY();
        $pk->z = $this->pos->getFloorZ();
        $pk->namedtag = $writer->write($tags);
        $this->player->dataPacket($pk);
        $this->inventory->getAdditionCompoundTags($tags, $this->pos);

        $pk = new ContainerOpenPacket();
        $pk->windowId = $this->player->getWindowId($this->inventory);
        $pk->type = $this->inventory->getNetworkType();

        $pk->x = $pk->y = $pk->z = 0;
        $pk->entityUniqueId = -1;

        if($this->pos instanceof Entity){
            $pk->entityUniqueId = $this->pos->getId();
        }else{
            $pk->x = $this->pos->getFloorX();
            $pk->y = $this->pos->getFloorY();
            $pk->z = $this->pos->getFloorZ();
        }
        $this->player->dataPacket($pk);
        $this->inventory->sendContents($this->player);

        $this->getHandler()->cancel();
    }
}