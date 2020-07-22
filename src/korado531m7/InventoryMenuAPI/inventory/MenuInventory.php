<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use korado531m7\InventoryMenuAPI\InventoryMenu;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;
use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\inventory\ContainerInventory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\tile\Nameable;
use pocketmine\utils\Utils;


abstract class MenuInventory extends ContainerInventory implements WindowTypes{
    /** @var \Closure */
    private $clickedCallable;
    /** @var \Closure */
    private $closedCallable;
    /** @var bool */
    private $readonly = true;

    public function __construct(){
        $this->title = $this->getBlock()->getName();
        parent::__construct(new Vector3());
    }

    /**
     * @param string $name
     */
    public function setName(string $name) : void{
        $this->title = $name;
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->title;
    }

    /**
     * @param bool $readonly
     */
    public function setReadonly(bool $readonly) : void{
        $this->readonly = $readonly;
    }

    /**
     * @return bool
     */
    public function isReadonly() : bool{
        return $this->readonly;
    }

    /**
     * Called when player clicked item
     *
     * @param \Closure $callable
     */
    public function setClickedCallable(\Closure $callable) : void{
        Utils::validateCallableSignature(function(Player $player, MenuInventory $inventory, Item $item) : void{}, $callable);

        $this->clickedCallable = $callable;
    }

    /**
     * @return \Closure|null
     */
    public function getClickedCallable() : ?\Closure{
        return $this->clickedCallable;
    }

    /**
     * Called when player closed this inventory
     *
     * @param \Closure $callable
     */
    public function setClosedCallable(\Closure $callable) : void{
        Utils::validateCallableSignature(function(Player $player, MenuInventory $inventory) : void{}, $callable);

        $this->closedCallable = $callable;
    }

    /**
     * @return \Closure|null
     */
    public function getClosedCallable() : ?\Closure{
        return $this->closedCallable;
    }

    public function getAdditionCompoundTags(CompoundTag $tag, Vector3 $pos) : void{

    }

    public function placeAdditionalBlocks(Player $player, Vector3 $pos) : void{

    }

    public function breakAdditionalBlocks(Player $player, Vector3 $pos) : void{

    }

    abstract public function getBlock() : Block;

    public function onOpen(Player $who) : void{
        parent::onOpen($who);
        $session = new Session($who->add(0, 4));
        InventoryMenu::newSession($who, $session);
        $holder = $session->getPosition();
        InventoryMenuUtils::sendFakeBlock($who, $holder, $this->getBlock());
        $this->placeAdditionalBlocks($who, $holder);

        InventoryMenu::getPluginBase()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($who, $holder) : void{
            $tags = new CompoundTag();
            $tags->setString(Nameable::TAG_CUSTOM_NAME, $this->getName());

            $writer = new NetworkLittleEndianNBTStream();
            $pk = new BlockActorDataPacket();
            $pk->x = $holder->getFloorX();
            $pk->y = $holder->getFloorY();
            $pk->z = $holder->getFloorZ();
            $pk->namedtag = $writer->write($tags);
            $who->dataPacket($pk);
            $this->getAdditionCompoundTags($tags, $holder);

            $pk = new ContainerOpenPacket();
            $pk->windowId = $who->getWindowId($this);
            $pk->type = $this->getNetworkType();

            $pk->x = $pk->y = $pk->z = 0;
            $pk->entityUniqueId = -1;

            if($holder instanceof Entity){
                $pk->entityUniqueId = $holder->getId();
            }else{
                $pk->x = $holder->getFloorX();
                $pk->y = $holder->getFloorY();
                $pk->z = $holder->getFloorZ();
            }
            $who->dataPacket($pk);
            $this->sendContents($who);
        }), 3);
    }

    public function onClose(Player $who) : void{
        parent::onClose($who);
        $session = InventoryMenu::getSession($who);
        if($session instanceof Session){
            InventoryMenuUtils::removeBlock($who, $session->getPosition());
            $this->breakAdditionalBlocks($who, $session->getPosition());
            InventoryMenu::resetSession($who);
        }
    }
}