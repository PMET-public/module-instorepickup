<?php

namespace MagentoEse\InStorePickup\Model;

use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class StoreLocationCookieManager
{
    /**
     * Cookie name
     */
    const COOKIE_NAME = 'store_location';

    /**
     * Cookie duration
     */
    const COOKIE_DURATION = 3600 * 24;

    /**
     * Cookie path
     */
    const COOKIE_PATH = '/';

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var String
     */
    protected $newCookieValue;

    /**
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param CookieManagerInterface $cookieManager
     */
    public function __construct(
        CookieMetadataFactory $cookieMetadataFactory,
        CookieManagerInterface $cookieManager
    ) {
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieManager = $cookieManager;
    }

    /**
     * @return null|string
     */
    public function getStoreLocationIdFromCookie()
    {
        if ($this->newCookieValue != null) {
            return $this->newCookieValue;
        } else {
            return $this->cookieManager->getCookie(self::COOKIE_NAME);
        }
    }

    /**
     * @param StoreLocation $storeLocation
     * @return void
     */
    public function setStoreCookie(StoreLocation $storeLocation)
    {
        $this->newCookieValue = $storeLocation->getId();

        $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setHttpOnly(false)
            ->setDuration(self::COOKIE_DURATION)
            ->setPath(self::COOKIE_PATH);

        $this->cookieManager->setPublicCookie(self::COOKIE_NAME, $storeLocation->getId(), $cookieMetadata);
    }

    /**
     * @param StoreLocation $storeLocation
     * @return void
     */
    public function deleteStoreCookie(StoreLocation $storeLocation)
    {
        $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setPath(self::COOKIE_PATH);

        $this->cookieManager->deleteCookie(self::COOKIE_NAME, $cookieMetadata);
    }
}
