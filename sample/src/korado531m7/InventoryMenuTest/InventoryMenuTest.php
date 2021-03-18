<?php
namespace korado531m7\InventoryMenuTest;


use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\InventoryMenu;
use korado531m7\InventoryMenuAPI\InventoryType;
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;
use korado531m7\InventoryMenuAPI\inventory\DispenserInventory;
use korado531m7\InventoryMenuAPI\inventory\DoubleChestInventory;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;

class InventoryMenuTest extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        //InventoryMenu::register($this); //to use virion framework, call this
    }
    
    public function onJoin(PlayerInteractEvent $event){
        $p = $event->getPlayer();
        if($p->isOp()){
            $item = [13 => Item::get(116)->setCustomName('§6INVENTORY MENU TESTING')->setLore(['','§aYou can use inventory menu api like this','§6It\'s awesome']), 38 => Item::get(276)->setCustomName('§6Calculation Test'), 42 => Item::get(331)->setCustomName('§aWhich do you like?'), 49 => Item::get(46)->setCustomName('§aCallable test')];

            $a = new DoubleChestInventory();
            $a->setContents($item);
            $a->setTitle('§bExample InventoryMenu');
            $a->setClickedCallable(function(Player $player, MenuInventory $inventory, Item $item) use ($a) : void{
                if($item->getId() === Item::TNT){
                    $player->addWindow($a);
                }
                if($item->getId() === Item::DIAMOND_SWORD){
                    $items = [0 => Item::get(332)->setCustomName('§l§b3'), 4 => Item::get(332)->setCustomName('§l§b6')];
                    $menu = InventoryMenu::createInventory(InventoryType::INVENTORY_TYPE_HOPPER);
                    $menu->setContents($items);
                    $menu->setTitle('§6What is 1 + 5?');
                    $player->addWindow($menu);
                }elseif($item->getId() === 331){
                    $items = [Item::get(260),Item::get(282),Item::get(297),Item::get(320),Item::get(349),Item::get(354),Item::get(357),Item::get(360),Item::get(364)];
                    $menu = new DispenserInventory();
                    $menu->setContents($items);
                    $menu->setTitle('§aWhich do you like?');
                    $player->addWindow($menu);
                }
            });
            $task = $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() use ($a) : void{
                $a->setItem(0, Item::get(mt_rand(1, 300)));
            }), 10);
            $a->setClosedCallable(function(Player $player, MenuInventory $inventory) use ($task) : void{
                /** @var TaskHandler $task */
                $task->cancel();
            });
            $p->addWindow($a);
        }
    }
    
    public function onClose(InventoryCloseEvent $event){
       //$event->getPlayer()->sendMessage('You closed an inventory. Sent from InventoryCloseEvent');
    }
    
    public function onClicked(InventoryClickEvent $event){
        $p = $event->getPlayer();
        /** @var MenuInventory $inv */
        $inv = $event->getInventory();
        $item = $event->getItem();
        switch($inv->getTitle()){
            case '§aWhich do you like?':
                $p->sendMessage('§aWow you like '.$item->getName().' !');
            break;

            case '§6What is 1 + 5?':
                if($item->getCustomName() === '§l§b3'){
                    $p->sendMessage('§cOh, that\'s incorrect :(');
                }elseif($item->getCustomName() === '§l§b6'){
                    $p->sendMessage('§aYeah! It\'s correct!');
                }
            break;
        }
    }
}
