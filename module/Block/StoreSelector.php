<?php

namespace MagentoEse\InStorePickup\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

/**
 * Store Selector block
 */
class StoreSelector extends Template
{
    /**
     * Retrieve form action url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @return string
     */
    public function getFormSearchActionUrl()
    {
        return $this->getUrl('instorepickup/storesearch/index', ['_secure' => $this->getRequest()->isSecure()]);
    }

    /**
     * Retrieve form action url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @return string
     */
    public function getFormSelectionActionUrl()
    {
        return $this->getUrl('instorepickup/storesearch/selection', ['_secure' => $this->getRequest()->isSecure()]);
    }
}
