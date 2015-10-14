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

            // Add column to quote and order tables
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

        $setup->endSetup();
    }
}
