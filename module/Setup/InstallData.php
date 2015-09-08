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
     * @var DirectoryStore
     */
    private $directoryStore;

    /**
     * Directory of Zipcodes
     *
     * @var DirectoryZipcode
     */
    private $directoryZipcode;

    /**
     * Init
     *
     * @param DirectoryStore $directoryStore
     * @param DirectoryZipcode $directoryZipcode
     */
    public function __construct(DirectoryStore $directoryStore, DirectoryZipcode $directoryZipcode)
    {
        $this->directoryStore = $directoryStore;
        $this->directoryZipcode = $directoryZipcode;
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
        $setup->getConnection()->insertArray($setup->getTable('directory_location_pickup_store'), $columns, $this->directoryStore->data);

        /**
         * Fill table directory_location_us_zip_code with sample data
         */
        $columns = ['zip', 'lat', 'lon', 'city', 'state', 'county', 'type'];
        $setup->getConnection()->insertArray($setup->getTable('directory_location_us_zip_code'), $columns, $this->directoryZipcode->data);
    }
}
