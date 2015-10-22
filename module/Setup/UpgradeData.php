<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\InStorePickup\Setup;

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
    public function __construct(
        BlockFactory $modelBlockFactory
    ) {
        $this->blockFactory = $modelBlockFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.6', '<')) {

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
        }

        $setup->endSetup();
    }
}
