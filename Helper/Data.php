<?php

namespace MagentoEse\InStorePickup\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\Store;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Block\Product\View\AbstractView;
/**
 * Auto Fill module base helper
 */
class Data extends AbstractHelper
{
    /**
     * Config value that indicates if Auto Fill is enabled
     */
    const CONFIG_PATH_ENABLED = 'magentoese_instorepickup/general/enable_instorepickup';

    /** @var AbstractView */
    protected $abstractView;

    public function __construct(Context $context,AbstractView $abstractView)
    {
        parent::__construct($context);
        $this->abstractView = $abstractView;
    }

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

    public function getProductId()
    {
        if($this->abstractView->getProduct()){
            return $this->abstractView->getProduct()->getId();
        }else{
            return 0;
        }

    }
}
