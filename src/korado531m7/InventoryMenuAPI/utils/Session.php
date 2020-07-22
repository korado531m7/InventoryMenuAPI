<?php


namespace korado531m7\InventoryMenuAPI\utils;


use pocketmine\math\Vector3;

class Session{
    /** @var Vector3 */
    private $pos;

    public function __construct(Vector3 $pos){
        $this->pos = $pos;
    }

    /**
     * @return Vector3
     */
    public function getPosition() : Vector3{
        return $this->pos;
    }
}