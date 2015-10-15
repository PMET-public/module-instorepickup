<?php

namespace MagentoEse\InStorePickup\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.2', '<')) {

            // Logging
            $this->_logger->info('MagentoEse_InStorePickup Schema Upgrade to 0.1.2');

            // Add column to quote and order tables for add to cart method
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_item'),
                'instorepickup_addtocart_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'In-Store Pickup Add To Cart Method'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_invoice_item'),
                'instorepickup_addtocart_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'In-Store Pickup Add To Cart Method'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_creditmemo_item'),
                'instorepickup_addtocart_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'In-Store Pickup Add To Cart Method'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_shipment_item'),
                'instorepickup_addtocart_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'In-Store Pickup Add To Cart Method'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('quote_item'),
                'instorepickup_addtocart_method',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'nullable' => true,
                    'comment' => 'In-Store Pickup Add To Cart Method'
                ]
            );
        }

        if (version_compare($context->getVersion(), '0.1.3', '<')) {

            // Logging
            $this->_logger->info('MagentoEse_InStorePickup Schema Upgrade to 0.1.3');

            // Add column to quote/order for flagging the presence of items to fulfill in store
            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'has_instorepickup_fulfillment',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 1,
                    'nullable' => false,
                    'default' => 0,
                    'unsigned' => true,
                    'comment' => 'Has items that are spcified to be fulfilled in store'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'has_instorepickup_fulfillment',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 1,
                    'nullable' => false,
                    'default' => 0,
                    'unsigned' => true,
                    'comment' => 'Has items that are spcified to be fulfilled in store'
                ]
            );

            // Add column to quote/order defining the store_location_id of the selected store for in-store pickup
            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'instorepickup_store_location_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'default' => 0,
                    'unsigned' => true,
                    'comment' => 'Store Location ID for In-Store Pickup'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'instorepickup_store_location_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'default' => 0,
                    'unsigned' => true,
                    'comment' => 'Store Location ID for In-Store Pickup'
                ]
            );
        }

        $setup->endSetup();
    }
}
