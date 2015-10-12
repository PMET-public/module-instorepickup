<?php

namespace MagentoEse\InStorePickup\Block;

use MagentoEse\InStorePickup\Model\Session;

/**
 * Store Selector block
 */
class StoreSelectorScopePrivate extends \MagentoEse\InStorePickup\Block\StoreSelector
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Session\StoreLocation $storeLocationSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Session\StoreLocation $storeLocationSession,
        array $data = []
    ) {
        parent::__construct($context, $storeLocationSession, $data);
        $this->_isScopePrivate = true;
    }
}
