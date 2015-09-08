<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;

class Index extends Action
{
    /**
     * @var \MagentoEse\InStorePickup\Model\Resource\StoreLocation\CollectionFactory
     */
    private $storeLocColFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @param Context $context
     * @param Data $jsonHelper
     * @param \MagentoEse\InStorePickup\Model\Resource\ZipcodeLocationFactory $zipLocFactory
     * @param \MagentoEse\InStorePickup\Model\Resource\StoreLocation\CollectionFactory $storeLocColFactory
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        \MagentoEse\InStorePickup\Model\Resource\StoreLocation\CollectionFactory $storeLocColFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->storeLocColFactory = $storeLocColFactory;
        parent::__construct($context);
    }
    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        $params = (array)$this->getRequest()->getParams();

        // Define parameters for looking up available stores
        $zipcode = $params['searchCriteria'];
        $distance = 50;
        $limitResults = 20;

        // Initialize the store location collection
        $storeLocCol = $this->createStoreLocColInstance();
        $storeLocCol->addZipcodeDistanceFilter($zipcode, $distance);
        $storeLocCol->setPageSize($limitResults);
        $storeLocCol->addOrder($storeLocCol::DISTANCE_COLUMN, $storeLocCol::SORT_ORDER_ASC);

        // Build the response data set
        $response = [];
        foreach ($storeLocCol as $storeLoc) {
            $response[] = [
                'id' => $storeLoc->getId(),
                'name' => $storeLoc->getName(),
                'street_address' => $storeLoc->getStreetAddress(),
                'city' => $storeLoc->getCity(),
                'state' => $storeLoc->getState(),
                'postal_code' => $storeLoc->getPostalCode(),
                'phone' => $storeLoc->getPhone(),
                'distance' => $storeLoc->getDistance()
            ];
        }

        // Represent the response as JSON and encode the response object as a JSON data set
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );

        // Bypass post the dispatch events from the app action
        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
    }

    /**
     * @return \MagentoEse\InStorePickup\Model\Resource\StoreLocation\Collection
     */
    protected function createStoreLocColInstance()
    {
        return $this->storeLocColFactory->create();
    }
}