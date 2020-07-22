<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use korado531m7\InventoryMenuAPI\utils\TradingMaterial;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\block\BlockIds;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\item\Item;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;

class VillagerInventory extends MenuInventory{
    /** @var TradingMaterial[] */
    private $materials = [];
    /** @var int */
    private $eid;
    /** @var Vector3 */
    private $position;
    
    public function __construct(Vector3 $position){
        parent::__construct();
        $this->position = $position;
        $this->eid = Entity::$entityCount++;
        $this->setReadonly(false);
        $this->setName('Villager Inventory');
    }
    
    public function addMaterial(TradingMaterial $recipe){
        $this->materials[] = $recipe;
    }

    /**
     * @return TradingMaterial[]
     */
    public function getMaterials() : array{
        return $this->materials;
    }
    
    public function getDefaultSize() : int{
        return 2;
    }
    
    public function getNetworkType() : int{
        return self::TRADING;
    }

    public function getBlock() : Block{
        return Block::get(Block::AIR);
    }

    public function onOpen(Player $who) : void{
        parent::onOpen($who);
        $this->sendTradingData($who);
    }

    public function onClose(Player $who) : void{
        $pk = new RemoveActorPacket();
        $pk->entityUniqueId = $this->eid;
        $who->dataPacket($pk);
        parent::onClose($who);
    }
    
    private function sendVillager(Player $player){
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->eid;
        $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[EntityIds::VILLAGER];
        $pk->position = $this->position;
        $pk->metadata = [
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, 1 << Entity::DATA_FLAG_IMMOBILE],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        $player->dataPacket($pk);
    }
    
    private function getTradeRecipeTags() : array{
        $recipes = [];
        foreach($this->getMaterials() as $material){
            $tag = new CompoundTag();
            $tag->setTag($material->getMaterial1()->nbtSerialize(-1, 'buyA'));
            $ing2 = $material->getMaterial2();
            if($ing2 instanceof Item){
                $tag->setTag($ing2->nbtSerialize(-1, 'buyB'));
            }
            $tag->setTag($material->getResult()->nbtSerialize(-1, 'sell'));
            $tag->setInt('maxUses', 32767);
            $tag->setInt('uses', 0);
            $tag->setByte('rewardExp', 0);
            $recipes[] = $tag;
        }
        return $recipes;
    }
    
    private function sendTradingData(Player $player){
        $this->sendVillager($player);
        $tag = new CompoundTag();
        $tag->setTag(new ListTag('Recipes', $this->getTradeRecipeTags()));
        $nbt = new NetworkLittleEndianNBTStream();
        $pk = new UpdateTradePacket;
        $pk->windowId = $player->getWindowId($this);
        $pk->tradeTier = 0;
        $pk->isV2Trading = false;
        $pk->isWilling = false;
        $pk->traderEid = $this->eid;
        $pk->playerEid = $player->getId();
        $pk->displayName = $this->getName();
        $pk->offers = $nbt->write($tag);
        $player->dataPacket($pk);
    }
}