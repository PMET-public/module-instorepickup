<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;

class Selection extends Action
{
    /**
     * @var Data
     */
    protected $jsonHelper;

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
     * @param Context $context
     * @param Data $jsonHelper
     * @param StoreLocationCookieManager $storeLocationCookieManager
     * @param StoreLocation $storeLocation
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        StoreLocationCookieManager $storeLocationCookieManager,
        StoreLocation $storeLocation
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->storeLocationCookieManager = $storeLocationCookieManager;
        $this->storeLocation = $storeLocation;
        parent::__construct($context);
    }
    /**
     * Selection action
     *
     * @return Page
     */
    public function execute()
    {
        $params = (array)$this->getRequest()->getParams();

        $response = [];
        $response['params'] = $params;

        // check for existing store
        $currentStoreLocationId = $this->storeLocationCookieManager->getStoreLocationIdFromCookie();

        $storeLocationId = $params['store-id'];
        $this->storeLocation->load($storeLocationId);
        if ($this->storeLocation != null) {

            $this->storeLocationCookieManager->setStoreCookie($this->storeLocation);

            // respond with selected store details
            $response['chosenStore'] = [
                'id' => $this->storeLocation->getId(),
                'name' => $this->storeLocation->getName()
            ];

            $response['success'] = true;
            $response['message'] = 'Store saved';

            // store location switched
            if ($currentStoreLocationId != null) {
                $response['locationSwitched'] = true;
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Error selecting a store';
        }

        // Represent the response as JSON and encode the response object as a JSON data set
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );

        // Bypass post the dispatch events from the app action
        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
    }
}