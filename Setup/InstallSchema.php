<?php

namespace MagentoEse\InStorePickup\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $connection = $setup->getConnection();
        try {
            $connection->beginTransaction();

            /**
             * Create table 'directory_location_us_zip_code'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_location_us_zip_code')
            )->addColumn(
                'zip',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                5,
                ['nullable' => false, 'primary' => true, 'default' => false],
                'Zipcode'
            )->addColumn(
                'lat',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                12,
                ['nullable' => true, 'default' => null],
                'Latitude'
            )->addColumn(
                'lon',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                12,
                ['nullable' => true, 'default' => null],
                'Longitude'
            )->addColumn(
                'city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'City'
            )->addColumn(
                'state',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                3,
                ['nullable' => true, 'default' => null],
                'State'
            )->addColumn(
                'county',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'County'
            )->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Zipcode Type'
            )->setComment(
                'Directory Location of Zipcodes'
            );
            $installer->getConnection()->createTable($table);

            /**
             * Create table 'directory_location_pickup_store'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('directory_location_pickup_store')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Store Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Name'
            )->addColumn(
                'street_address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Street Address'
            )->addColumn(
                'city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'City'
            )->addColumn(
                'state',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                5,
                ['nullable' => true, 'default' => null],
                'State'
            )->addColumn(
                'postal_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                5,
                ['nullable' => true, 'default' => null],
                'Postal Code'
            )->addColumn(
                'phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Phone'
            )->addColumn(
                'lat_deg',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Latitude in Degrees'
            )->addColumn(
                'lon_deg',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Longitude in Degrees'
            )->addColumn(
                'lat_rad',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Latitude Radians'
            )->addColumn(
                'lon_rad',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Longitude Radians'
            )->addColumn(
                'real_phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Real Phone'
            )->setComment(
                'Directory Location of Stores Supporting In-Store Pickup'
            );
            $installer->getConnection()->createTable($table);

            $connection->commit();
        } catch (\Exception $e) {

            // If an error occured rollback the database changes as if they never happened
            $connection->rollback();
            throw $e;
        }
        $installer->endSetup();

    }
}
