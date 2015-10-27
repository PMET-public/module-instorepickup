<?php

namespace MagentoEse\InStorePickup\Model\Session;

use Magento\Framework\Session;
use MagentoEse\InStorePickup\Model\StoreLocationFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\App\State;
use Magento\Framework\App\Request\Http;

/**
 * Store Location session model
 */
class StoreLocation extends Session\SessionManager
{
    /**
     * The property name associated with the storage of Store Location session data
     */
    const SESSION_PROPERTY_NAME = 'store_location';

    /**
     * @var Session\Generic
     */
    protected $session;

    /**
     * @var StoreLocationFactory
     */
    protected $storeLocationFactory;

    /**
     * @var \MagentoEse\InStorePickup\Model\StoreLocation
     */
    protected $storeLocation;

    /**
     * @param Http $request
     * @param Session\SidResolverInterface $sidResolver
     * @param Session\Config\ConfigInterface $sessionConfig
     * @param Session\SaveHandlerInterface $saveHandler
     * @param Session\ValidatorInterface $validator
     * @param Session\StorageInterface $storage
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\App\State $appState
     * @param Session\Generic $session
     * @param StoreLocationFactory $storeLocationFactory
     */
    public function __construct(
        Http $request,
        Session\SidResolverInterface $sidResolver,
        Session\Config\ConfigInterface $sessionConfig,
        Session\SaveHandlerInterface $saveHandler,
        Session\ValidatorInterface $validator,
        Session\StorageInterface $storage,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        State $appState,
        Session\Generic $session,
        StoreLocationFactory $storeLocationFactory
    ) {
        $this->session = $session;
        $this->storeLocationFactory = $storeLocationFactory;
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState
        );
    }

    /**
     * Set store location
     *
     * @param \MagentoEse\InStorePickup\Model\StoreLocation $storeLocation
     * @return $this
     */
    public function setStoreLocation(\MagentoEse\InStorePickup\Model\StoreLocation $storeLocation)
    {
        // Check to see if a valid store location was passed in
        if ($storeLocation->getId() > 0) {
            // define the data stored in the session
            $data = [
                'id' => $storeLocation->getId()
            ];

            // Save the store location data into session storage
            $this->storage->setData(static::SESSION_PROPERTY_NAME, $data);

            // save the store location to a class property for more efficient multiple gets
            $this->storeLocation = $storeLocation;
        }
        return $this;
    }

    /**
     * Retrieve store location from current session
     *
     * @return \MagentoEse\InStorePickup\Model\StoreLocation|null
     */
    public function getStoreLocation()
    {
        // check to see if we already have retrieved the store location
        if ($this->storeLocation == null) {

            // Get any store location data stored in the session
            $data = $this->storage->getData(static::SESSION_PROPERTY_NAME);
            if ($data != null) {
                /* @var \MagentoEse\InStorePickup\Model\StoreLocation $storeLocation */
                $storeLocation = $this->storeLocationFactory->create();
                $storeLocation->load($data['id']);

                // save the store location to a class property for more efficient multiple gets
                if ($storeLocation->getId() > 0) $this->storeLocation = $storeLocation;
                return $this->storeLocation;
            }
            return null;
        } else {
            return $this->storeLocation;
        }
    }

    /**
     * Check to see if the session contains a selected store location
     * @return bool
     */
    public function hasStoreLocationSession() {
        return $this->getStoreLocation() != null;
    }
}
