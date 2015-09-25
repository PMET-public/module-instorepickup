<?php

namespace MagentoEse\InStorePickup\Block;

use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;

/**
 * Store Selector block
 */
class StoreSelector extends \Magento\Framework\View\Element\Template
{
    /**
     * Store Location Cookie Manager
     *
     * @var StoreLocationCookieManager
     */
    protected $storeLocationCookieManager;

    /**
     * Store Location
     *
     * @var StoreLocation
     */
    protected $storeLocation;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param StoreLocationCookieManager $storeLocationCookieManager
     * @param StoreLocation $storeLocation
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        StoreLocationCookieManager $storeLocationCookieManager,
        StoreLocation $storeLocation,
        array $data = []
    ) {
        $this->storeLocationCookieManager = $storeLocationCookieManager;
        $this->storeLocation = $storeLocation;
        parent::__construct($context, $data);
    }

    /**
     * Flag indicating if a store has been chosen already
     *
     * @return bool
     */
    public function hasStoreBeenChosen()
    {
        // Return true if there is an ID value
        return $this->storeLocationCookieManager->getStoreLocationIdFromCookie() > 0;
    }

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
