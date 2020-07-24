<?php


namespace korado531m7\InventoryMenuAPI\task;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\scheduler\Task;
use pocketmine\tile\Nameable;

class SendInventoryTask extends Task{
    /** @var MenuInventory */
    private $inventory;
    /** @var Session */
    private $session;

    public function __construct(MenuInventory $inventory, Session $session){
        $this->inventory = $inventory;
        $this->session = $session;
    }

    public function onRun(int $currentTick){
        $player = $this->session->getPlayer();
        $pos = $this->session->getPosition();
        $eid = $this->session->getEid();
        $tags = new CompoundTag();
        $tags->setString(Nameable::TAG_CUSTOM_NAME, $this->inventory->getTitle());

        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockActorDataPacket();
        $pk->x = $pos->getX();
        $pk->y = $pos->getY();
        $pk->z = $pos->getZ();
        $pk->namedtag = $writer->write($tags);
        $player->dataPacket($pk);
        $this->inventory->getAdditionCompoundTags($tags, $pos);

        $pk = new ContainerOpenPacket();
        $pk->windowId = $player->getWindowId($this->inventory);
        $pk->type = $this->inventory->getNetworkType();
        $pk->x = $pos->getX();
        $pk->y = $pos->getY();
        $pk->z = $pos->getZ();
        if($eid !== null){
            $pk->entityUniqueId = $eid;
        }
        $player->dataPacket($pk);
        $this->inventory->sendContents($player);

        $this->getHandler()->cancel();
    }
}