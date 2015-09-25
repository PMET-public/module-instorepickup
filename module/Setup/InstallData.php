<?php

namespace MagentoEse\InStorePickup\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Directory of Stores
     *
     * @var array
     */
    private $directoryStore;

    /**
     * Directory of Zipcodes
     *
     * @var array
     */
    private $directoryZipcode;

    /**
     * Init
     */
    public function __construct()
    {
        $this->directoryStore = require 'DirectoryStore.php';
        $this->directoryZipcode = require 'DirectoryZipcode.php';
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Fill table directory_location_pickup_store with sample data
         */
        $columns = ['name', 'street_address', 'city', 'state', 'postal_code', 'phone', 'lat_deg', 'lon_deg', 'lat_rad', 'lon_rad', 'real_phone'];
        $setup->getConnection()->insertArray($setup->getTable('directory_location_pickup_store'), $columns, $this->directoryStore);

        /**
         * Fill table directory_location_us_zip_code with sample data
         */
        $columns = ['zip', 'lat', 'lon', 'city', 'state', 'county', 'type'];
        foreach ($this->directoryZipcode as $chunk) {
            $setup->getConnection()->insertArray($setup->getTable('directory_location_us_zip_code'), $columns, $chunk);
        }
    }
}
