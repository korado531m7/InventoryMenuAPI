<?php
/** @noinspection PhpUnusedParameterInspection */

namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;

use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class EventListener implements Listener{
    /** @var PluginBase */
    private $pluginBase;

    public function __construct(PluginBase $pluginBase){
        $this->pluginBase = $pluginBase;
    }

    public function onReceive(DataPacketReceiveEvent $event) : void{
        $player = $event->getPlayer();
        $pk = $event->getPacket();
        switch(true){
            case $pk instanceof ContainerClosePacket:
                $inventory = $player->getWindow($pk->windowId);
                if($inventory instanceof MenuInventory){
                    $ev = new InventoryCloseEvent($player, $inventory, $pk->windowId);
                    $ev->call();
                    $callable = $inventory->getClosedCallable();
                    if($callable !== null){
                        $callable($ev);
                    }
                    if($ev->isCancelled()){
                        $this->pluginBase->getScheduler()->scheduleDelayedTask(new ClosureTask(function(int $currentTick) use ($player, $inventory) : void{
                            $player->addWindow($inventory);
                        }), 3);
                    }
                }
                break;

            case $pk instanceof InventoryTransactionPacket:
                foreach($pk->actions as $action){
                    if($action instanceof NetworkInventoryAction && $action->windowId !== null){
                        $inv = $player->getWindow($action->windowId);
                        if($inv instanceof MenuInventory){
                            $item = $action->oldItem;
                            $ev = new InventoryClickEvent($player, $item, $inv);
                            $ev->call();
                            $callable = $inv->getClickedCallable();
                            if($callable !== null){
                                $callable($ev);
                            }
                            if($inv->isReadonly()){
                                $session = InventoryMenu::getSession($player);
                                if($session instanceof Session){
                                    $session->restoreBlock();
                                }
                            }
                            if($inv->isReadonly() || $ev->isCancelled()){
                                $item->setCount(0);
                                $player->getCursorInventory()->setItem(0, $player->getCursorInventory()->getItem(0));
                                // Refresh item cache instead of Inventory -> sendContents because it doesn't work on windows10
                            }
                        }
                    }
                }
                break;
        }
    }

}