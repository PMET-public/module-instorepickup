<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\InStorePickup\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @param BlockFactory $modelBlockFactory
     */

     /**
      * 
      * @var EavSetupFactory
      */
    private $eavSetupFactory;

    /**
     * 
     * @var array
     */
    private $inventoryStore;

    /**
     * 
     * @param Magento\Cms\Model\BlockFactory $modelBlockFactory 
     * @param Magento\Eav\Setup\EavSetupFactory $eavSetupFactory 
     * @return void 
     */    

    public function __construct(
        BlockFactory $modelBlockFactory,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->blockFactory = $modelBlockFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->inventoryStore = require 'StoreInventory.php';
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.6', '<')) {

            $connection = $setup->getConnection();
            try {
                $connection->beginTransaction();

                $content = <<<EOD
<div class="pickup-success-message">
    <h3>Next Steps</h3>
    <div>
        <p><b>Step 1: Wait for your "Ready for Store Pickup" email</b></p>
        <p>You will receive 3 emails. The first will acknowledge that we have received your order. The second email will confirm your order is "Ready for Store Pickup".</p>
    </div>
    <div>
        <p><b>Step 2: Bring the following items to the store</b></p>
        <ul>
            <li>The "Ready for Store Pickup" email (one for each store selected).</li>
            <li>Valid ID: A drivers license or government-issued ID</li>
            <li>Credit Card used for purchase</li>
        </ul>
    </div>
</div>
EOD;

                $cmsBlock = [
                    'title' => 'Checkout Onepage Success - In-Store Pickup',
                    'identifier' => 'onepage_success_instorepickup',
                    'content' => $content,
                    'is_active' => 1,
                    'stores' => 0,
                ];

                /** @var \Magento\Cms\Model\Block $block */
                $block = $this->blockFactory->create();
                $block->setData($cmsBlock)->save();

                $connection->commit();
            } catch (\Exception $e) {

                // If an error occured rollback the database changes as if they never happened
                $connection->rollback();
                throw $e;
            }
        }

        if (version_compare($context->getVersion(), '0.1.7', '<')) {

            $connection = $setup->getConnection();
            try {
                $connection->beginTransaction();
                foreach ($this->inventoryStore as $store) {
                    if ($installer->getTableRow($installer->getTable('directory_location_pickup_store'), 'name', $store[0])) {
                        $installer->updateTableRow(
                            $installer->getTable('directory_location_pickup_store'),
                            'name',
                            $store[0],
                            'inventory',
                            $store[1]
                        );
                    }
                }
                /** @var EavSetup $eavSetup */
                $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

                /**
                 * Update attribute to be included in the admin grid and layered nav
                 */

                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'in_store_available',
                    [
                        'is_filterable' => true,
                        'is_visible_in_grid' => true,
                        'is_used_in_grid' => true,
                        'is_filterable_in_grid' => true,
                        'is_filterable_in_search' => true,
                        'is_searchable_in_grid' => true,
                    ]
                );

                $connection->commit();
            } catch (\Exception $e) {

                // If an error occured rollback the database changes as if they never happened
                $connection->rollback();
                throw $e;
            }
        }

        $setup->endSetup();
    }
}
