<?php

namespace MagentoEse\InStorePickup\Block;

use MagentoEse\InStorePickup\Model\Session;

/**
 * Store Selector block
 */
class StoreSelector extends \Magento\Framework\View\Element\Template
{
    /**
     * Store Location session
     *
     * @var Session\StoreLocation
     */
    protected $storeLocationSession;

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
        $this->storeLocationSession = $storeLocationSession;
        parent::__construct($context, $data);
    }

    /**
     * Get the current chosen store location
     *
     * @return \MagentoEse\InStorePickup\Model\StoreLocation
     */
    public function getChosenStoreLocation()
    {
        return $this->storeLocationSession->getStoreLocation();
    }

    /**
     * Flag indicating if a store has been chosen already
     *
     * @return bool
     */
    public function hasStoreBeenChosen()
    {
        return $this->storeLocationSession->hasStoreLocationSession();
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

    /**
     * Retrieve directions url
     *
     * @return string
     */
    public function getDirectionsUrl()
    {
        // Get the chosen store
        $storeLocation = $this->storeLocationSession->getStoreLocation();

        // Build a string representing the store address
        $destinationAddress =
            $storeLocation->getStreetAddress() . ", " .
            $storeLocation->getCity() . ", " . $storeLocation->getState() . " " . $storeLocation->getPostalCode();

        // URL Encode the address
        $destinationAddress = urlencode($destinationAddress);

        $url = "http://maps.google.com/maps?daddr=" . $destinationAddress;
        return $url;
    }
}
