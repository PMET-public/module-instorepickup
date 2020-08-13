<?php

namespace MagentoEse\InStorePickup\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use MagentoEse\InStorePickup\Helper\Product;
/**
 * Auto Fill module base helper
 */
class Data extends AbstractHelper
{
    /**
     * Config value that indicates if Auto Fill is enabled
     */
    const CONFIG_PATH_ENABLED = 'magentoese_instorepickup/general/enable_instorepickup';

    /** @var Product */
    protected $productHelper;

    public function __construct(Context $context,Product $productHelper)
    {
        parent::__construct($context);
        $this->productHelper = $productHelper;
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
        if($this->productHelper->getProduct()){
            return $this->productHelper->getProduct()->getId();
        }else{
            return 0;
        }

    }
}
