<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;

use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class EventListener implements Listener{
    /** @var PluginBase */
    private $pluginBase;

    public function __construct(PluginBase $pluginBase){
        $this->pluginBase = $pluginBase;
    }

    public function onTransaction(InventoryTransactionEvent $event) : void{
        foreach($event->getTransaction()->getActions() as $action){
            if($action instanceof SlotChangeAction){
                $inv = $action->getInventory();
                if($inv instanceof MenuInventory){
                    $player = $event->getTransaction()->getSource();
                    $item = $action->getSourceItem()->getId() === Item::AIR ? $action->getTargetItem() : $action->getSourceItem();
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
                        $event->setCancelled();
                    }
                }
            }
        }
    }

    public function onReceive(DataPacketReceiveEvent $event) : void{
        $player = $event->getPlayer();
        $pk = $event->getPacket();
        if($pk instanceof ContainerClosePacket){
            $inventory = $player->getWindow($pk->windowId);
            if($inventory instanceof MenuInventory){
                $ev = new InventoryCloseEvent($player, $inventory);
                $ev->call();
                $callable = $inventory->getClosedCallable();
                if($callable !== null){
                    $callable($ev);
                }
                if($ev->isCancelled()){
                    $this->pluginBase->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player, $inventory) : void{
                        $player->addWindow($inventory);
                    }), 3);
                }
            }
        }
    }

}