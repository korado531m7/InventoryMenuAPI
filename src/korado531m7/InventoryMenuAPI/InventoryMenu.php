<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\MenuInventory;
use korado531m7\InventoryMenuAPI\utils\Session;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class InventoryMenu extends PluginBase implements InventoryType{
    /** @var Session[] */
    private static $sessions = [];
    private static $pluginbase = null;
    
    public function onEnable(){
        self::register($this);
        $this->getLogger()->notice('You are using this api as plugin. We recommend you to use this as virion');
    }
    
    /**
     * You need to call this function statically to use this api
     *
     * @param PluginBase $plugin
     */
    public static function register(PluginBase $plugin) : void{
        if(self::$pluginbase !== null){
            throw new RuntimeException('Plugin base has been registered');
        }

        self::$pluginbase = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents(new EventListener($plugin), $plugin);
    }

    /**
     * Create Inventory
     *
     * @param string $type
     *
     * @return MenuInventory
     * @throws ReflectionException
     */
    public static function createInventory(string $type = self::INVENTORY_TYPE_CHEST) : MenuInventory{
        $class = new ReflectionClass($type);
        if(!is_a($type, MenuInventory::class, true) || $class->isAbstract()){
            throw new ReflectionException('Class ' . $class->getName() . ' is not valid');
        }
        return new $type();
    }

    public static function newSession(Player $player, Session $session) : void{
        self::$sessions[$player->getId()] = $session;
    }

    public static function getSession(Player $player) : ?Session{
        return self::$sessions[$player->getId()] ?? null;
    }

    public static function resetSession(Player $player) : void{
        unset(self::$sessions[$player->getId()]);
    }
    
    /**
     * this function is for internal use only. Don't call this
     */
    public static function getPluginBase() : PluginBase{
        return self::$pluginbase;
    }
}