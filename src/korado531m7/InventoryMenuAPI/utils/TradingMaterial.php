<?php


namespace korado531m7\InventoryMenuAPI\utils;


use pocketmine\item\Item;

class TradingMaterial{
    /** @var Item */
    private $material1;
    /** @var Item|null */
    private $material2;
    /** @var Item */
    private $result;

    public function __construct(Item $material1, Item $result, ?Item $material2 = null){
        $this->material1 = $material1;
        $this->material2 = $material2;
        $this->result = $result;
    }
    
    public function setResult(Item $item){
        $this->result = $item;
    }
    
    public function getMaterial1() : Item{
        return $this->material1;
    }
    
    public function getMaterial2() : ?Item{
        return $this->material2;
    }
    
    public function getResult() : Item{
        return $this->result;
    }

}