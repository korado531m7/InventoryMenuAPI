# InventoryMenuAPI
**Fake and Menu Inventory API for PocketMine**


### Installation
You can download converted to phar version file from [here](https://poggit.pmmp.io/ci/korado531m7/InventoryMenuAPI/InventoryMenuAPI)


### Known Issues
* Villager Inventory doesn't work correctly


### Preparation
Before using, you need to import class
```php
use korado531m7\InventoryMenuAPI\InventoryMenu;
```

If you use api as virion, you must call register function statically
```php
InventoryMenu::register($param); //param must be PluginBase
```

___

### Sending Inventory
First, you need to make an array that is including items
```php
$array = [3 => Item::get(4,0,1), 7 => Item::get(46,0,5), 12 => Item::get(246,0,1), 14 => Item::get(276,0,1)->setCustomName('MysterySword!')];
```
these items will be set on inventory menu

To send inventory menu, create InventoryMenu instance
```php
//use korado531m7\InventoryMenuAPI\inventory\ChestInventory;

$inv = new ChestInventory();
$inv = InventoryMenu::createInventory();
```
(you don't have to write use sentence ChestInventory if you use createInventory function)

then, call send function with addWindow from Player
```php
$player->addWindow($inv); //$player is player object
```

___

**SET READONLY (WRITABLE) INVENTORY**

To allow to trade, use setReadonly function (default value is true)
```php
$inv->setReadonly(false); //boolean
```

___

**RENAMING INVENTORY MENU NAME**

To change inventory name, call setTitle function
```php
$inv->setTitle('WRITE NAME HERE');
```

___

**CHANGING INVENTORY TYPE**

To change inventory type, you need to create each inventory instance, or change the parameter when use createInventory function
```php
//Supported list
const INVENTORY_TYPE_ANVIL = AnvilInventory::class;
const INVENTORY_TYPE_BEACON = BeaconInventory::class;
const INVENTORY_TYPE_BREWING_STAND = BrewingStandInventory::class;
const INVENTORY_TYPE_CHEST = ChestInventory::class;
const INVENTORY_TYPE_DISPENSER = DispenserInventory::class;
const INVENTORY_TYPE_DOUBLE_CHEST = DoubleChestInventory::class;
const INVENTORY_TYPE_DROPPER = DropperInventory::class;
const INVENTORY_TYPE_ENCHANTING_TABLE = EnchantingTableInventory::class;
const INVENTORY_TYPE_HOPPER = HopperInventory::class;
const INVENTORY_TYPE_VILLAGER = VillagerInventory::class;
```

```php
//Example:
$inv = new EnchantingTableInventory();
$inv = InventoryMenu::createInventory(InventoryType::INVENTORY_TYPE_ENCHANTING_TABLE);
```
These constants are written in `korado531m7\InventoryMenuAPI\InventoryType`

___

**Set callback**

you can set callable and will be called when player clicked an item and closed inventory.

To call callable when clicked something, use `setClickedCallable`
```php
public function setClickedCallable(Closure $closure) : void;

//Ex: function(InventoryClickEvent $event) : void{
    $player->sendMessage('Clicked: ' . $event->getItem()->getName());
}
```

To call callable when closed inventory, use `setClosedCallable`
```php
public function setClosedCallable(Closure $closure) : void;

//Ex: function(InventoryCloseEvent $event) : void{
    $event->getPlayer()->sendMessage('Closed');
}
```


___

**Task feature has been removed**

but, you can share all inventories with some people. That is to say you can rewrite items while player is opening.

Here's sample
```php
/** @var ChestInventory $inv */
$player->addWindow($inv);

$schueduler->scheduleDelayedTask(new ClosureTask(function() use ($inv) : void{
    $inv->setItem(5, Item::get(Item::STONE));
}), 20 * 20); //it will be overwritten the inventory
```
___

**SET MATERIALS TO VILLAGER INVENTORY**

Since 3.2.0, you can create villager inventory and set material to it.
To make recipe, create TradingMaterial instance and set ingredients to that, then set it to villager inventory with addMaterial().
Here's example:
```php
//use korado531m7\InventoryMenuAPI\inventory\VillagerInventory;
$villagerInventory = new VillagerInventory();

//use korado531m7\InventoryMenuAPI\utils\TradingMaterial;
$recipe = new TradingMaterial(Item::get(Item::DIAMOND), Item::get(Item::TRIDENT), Item::get(Item::ENDER_EYE));
//The ingredients are diamond and ender eye. these are turn into trident. (To set one of ingredient, make 3 param empty or null)
$villagerInventory->addMaterial($recipe); //add material data to villager inventory
$player->addWindow(villagerInventory); //send to player
```

___

**WRITING CODE IN A ROW**
You can write code in a row.
```php
//Ex1
$player->addWindow(new ChestInventory());

//Ex2
$player->addWindow(InventoryMenu::createInventory()->setName('Test'));
```

___

### DEALING WITH EVENT
This api will call event and you can use that! This event also will be called in callable

**WHEN CLICK ITEM**

You can use event when player clicked items.
it's InventoryClickEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryClickEvent;
```
* `getPlayer()`               - Return Player object who clicked
* `getItem()`                 - Return Item which player clicked
* `getInventory()`            - Return Inventory
* `setCancelled(bool $value)` - To cancel, use this     (from Cancellable)
* `isCancelled()`             - Check whether cancelled (from Cancellable)

___

**WHEN CLOSE INVENTORY MENU**

You can use event when player close inventory.
it's InventoryCloseEvent
here's the documentation
```php
use korado531m7\InventoryMenuAPI\event\InventoryCloseEvent;
```
* `getPlayer()`               - Return Player object who clicked
* `getInventory()`            - Return Inventory
* `getWindowId()`             - Return Window Id
* `setCancelled(bool $value)` - To cancel, use this     (from Cancellable)
* `isCancelled()`             - Check whether cancelled (from Cancellable)

___

### REPORTING ISSUES
If you found a bug or issues, please report it at 'issue'
I'll fix that

___

### More Features
I'll make more features if have free time.
