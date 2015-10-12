<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\InStorePickup\Model\Session;

/**
 * Customer session model
 * @method string getNoReferer()
 */
class StoreLocation extends \Magento\Framework\Session\SessionManager
{
    /**
     * The property name associated with the storage of Store Location session data
     */
    const SESSION_PROPERTY_NAME = 'store_location';

    /**
     * @var \Magento\Framework\Session\Generic
     */
    protected $session;

    /**
     * @var \MagentoEse\InStorePickup\Model\StoreLocationFactory
     */
    protected $storeLocationFactory;

    /**
     * @var \MagentoEse\InStorePickup\Model\StoreLocation
     */
    protected $storeLocation;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Session\SidResolverInterface $sidResolver
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     * @param \Magento\Framework\Session\SaveHandlerInterface $saveHandler
     * @param \Magento\Framework\Session\ValidatorInterface $validator
     * @param \Magento\Framework\Session\StorageInterface $storage
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Session\Generic $session
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig,
        \Magento\Framework\Session\SaveHandlerInterface $saveHandler,
        \Magento\Framework\Session\ValidatorInterface $validator,
        \Magento\Framework\Session\StorageInterface $storage,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Session\Generic $session,
        \MagentoEse\InStorePickup\Model\StoreLocationFactory $storeLocationFactory
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
