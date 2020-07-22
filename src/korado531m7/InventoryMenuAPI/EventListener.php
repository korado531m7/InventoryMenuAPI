<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;
use korado531m7\InventoryMenuAPI\utils\InventoryMenuUtils;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\plugin\PluginBase;

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
                    $callable = $inv->getClickedCallable();
                    if($callable !== null){
                        $callable($player, $inv, $item);
                    }
                    $ev = new InventoryClickEvent($player, $item, $inv);
                    $ev->call();
                    if($inv->isReadonly()){
                        $action->getInventory()->removeItem($item);
                        InventoryMenuUtils::removeBlock($player, $player->add(0, 4)); //Can't use Player->removeWindow
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
                if($ev->isCancelled()){
                    $player->addWindow($inventory);
                }
                $callable = $inventory->getClosedCallable();
                if($callable !== null){
                    $callable($player, $inventory);
                }
            }
        }
    }

}