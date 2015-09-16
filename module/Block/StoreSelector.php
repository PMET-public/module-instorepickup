<?php

namespace MagentoEse\InStorePickup\Block;

/**
 * Store Selector block
 */
class StoreSelector extends \Magento\Framework\View\Element\Template
{
    /**
     * Retrieve form action url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @return string
     */
    public function getFormActionUrl()
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

    /**
     * Retrieve store detail url and set "secure" param
     *
     * @return string
     */
    public function getStoreDetailUrl()
    {
        return $this->getUrl('instorepickup/storesearch/detail', ['_secure' => $this->getRequest()->isSecure()]);
    }
}
