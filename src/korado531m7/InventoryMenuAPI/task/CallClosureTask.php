<?php


namespace korado531m7\InventoryMenuAPI\task;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use Closure;

class CallClosureTask extends Task{
    /** @var Closure */
    private $closure;
    /** @var Player */
    private $player;
    /** @var MenuInventory */
    private $inventory;
    /** @var Item|null */
    private $item;

    public function __construct(Closure $closure, Player $player, MenuInventory $inventory, ?Item $item = null){
        $this->closure = $closure;
        $this->player = $player;
        $this->inventory = $inventory;
        $this->item = $item;
    }

    public function onRun(int $currentTick){
        ($this->closure)($this->player, $this->inventory, $this->item);
        $this->getHandler()->cancel();
    }
}