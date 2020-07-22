<?php
namespace korado531m7\InventoryMenuAPI;

use korado531m7\InventoryMenuAPI\inventory\{AnvilInventory,
    BeaconInventory,
    BrewingStandInventory,
    ChestInventory,
    DispenserInventory,
    DoubleChestInventory,
    DropperInventory,
    EnchantingTableInventory,
    HopperInventory,
    VillagerInventory
};

interface InventoryType{
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
}