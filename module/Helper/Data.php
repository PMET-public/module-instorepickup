<?php

namespace MagentoEse\InStorePickup\Helper;

use Magento\Store\Model\Store;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Auto Fill module base helper
 */
class Data extends AbstractHelper
{
    /**
     * Config value that indicates if Auto Fill is enabled
     */
    const CONFIG_PATH_ENABLED = 'magentoese_instorepickup/general/enable_instorepickup';

    /**
     * Check if Auto Fill is enabled
     *
     * @return bool
     */
    public function isInStorePickupEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
