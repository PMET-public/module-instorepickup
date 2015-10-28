<?php

namespace MagentoEse\InStorePickup\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
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
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
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

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'in_store_available',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Available in Store',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 'Online Exclusive',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'option' => [
                    'values' => [
                        'In Store',
                        'Online Exclusive'
                    ]
                ]
            ]
        );
    }
}
