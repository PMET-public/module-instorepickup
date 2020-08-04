<?php

namespace MagentoEse\InStorePickup\Block;

use MagentoEse\InStorePickup\Model\Session;
use Magento\Framework\View\Element\Template\Context;

/**
 * Store Selector block
 */
class StoreSelectorScopePrivate extends StoreSelector
{
    /**
     * Store Location session
     *
     * @var Session\StoreLocation
     */
    protected $storeLocationSession;

    /**
     * @param Context $context
     * @param Session\StoreLocation $storeLocationSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session\StoreLocation $storeLocationSession,
        array $data = []
    ) {
        $this->storeLocationSession = $storeLocationSession;
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
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
            $storeLocation->getStreet() . ", " .
            $storeLocation->getCity() . ", " . $storeLocation->getState() . " " . $storeLocation->getPostcode();

        // URL Encode the address
        $destinationAddress = urlencode($destinationAddress);

        $url = "http://maps.google.com/maps?daddr=" . $destinationAddress;
        return $url;
    }
}
