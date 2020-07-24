<?php


namespace korado531m7\InventoryMenuAPI\inventory;


use korado531m7\InventoryMenuAPI\InventoryMenu;
use korado531m7\InventoryMenuAPI\task\SendInventoryTask;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;
use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\block\Block;
use pocketmine\inventory\ContainerInventory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\utils\Utils;
use Closure;

abstract class MenuInventory extends ContainerInventory implements WindowTypes{
    /** @var Closure */
    private $clickedCallable;
    /** @var Closure */
    private $closedCallable;
    /** @var bool */
    private $readonly = true;

    public function __construct(){
        $this->title = $this->getBlock()->getName();
        parent::__construct(new Vector3());
    }

    /**
     * @param string $title
     */
    final public function setTitle(string $title) : void{
        $this->title = $title;
    }

    /**
     * @deprecated use setTitle
     *
     * @param string $name
     */
    final public function setName(string $name) : void{
        $this->title = $name;
    }

    /**
     * @param bool $readonly
     */
    final public function setReadonly(bool $readonly) : void{
        $this->readonly = $readonly;
    }

    /**
     * @return bool
     */
    final public function isReadonly() : bool{
        return $this->readonly;
    }

    /**
     * Called when player clicked item
     *
     * @param Closure $callable
     */
    final public function setClickedCallable(Closure $callable) : void{
        Utils::validateCallableSignature(function(Player $player, MenuInventory $inventory, Item $item) : void{}, $callable);

        $this->clickedCallable = $callable;
    }

    /**
     * @return Closure|null
     */
    final public function getClickedCallable() : ?Closure{
        return $this->clickedCallable;
    }

    /**
     * Called when player closed this inventory
     *
     * @param Closure $callable
     */
    final public function setClosedCallable(Closure $callable) : void{
        Utils::validateCallableSignature(function(Player $player, MenuInventory $inventory) : void{}, $callable);

        $this->closedCallable = $callable;
    }

    /**
     * @return Closure|null
     */
    final public function getClosedCallable() : ?Closure{
        return $this->closedCallable;
    }

    public function getAdditionCompoundTags(CompoundTag $tag, Vector3 $pos) : void{

    }

    public function placeAdditionalBlocks(Session $session, Vector3 $pos) : void{

    }

    public function breakAdditionalBlocks(Player $player, Vector3 $pos) : void{

    }

    public function getEid() : int{
        return -1;
    }

    abstract public function getBlock() : Block;

    public function onOpen(Player $who) : void{
        parent::onOpen($who);
        $session = new Session($who, $this->getEid());
        InventoryMenu::newSession($who, $session);
        InventoryMenuUtils::sendFakeBlock($session, $session->getPosition(), $this->getBlock());
        $this->placeAdditionalBlocks($session, $session->getPosition());

        InventoryMenu::getPluginBase()->getScheduler()->scheduleDelayedTask(new SendInventoryTask($this, $session), 3);
    }

    public function onClose(Player $who) : void{
        parent::onClose($who);
        $session = InventoryMenu::getSession($who);
        if($session instanceof Session){
            $session->restoreBlock();
            $this->breakAdditionalBlocks($who, $session->getPosition());
            InventoryMenu::resetSession($who);
        }
    }
}