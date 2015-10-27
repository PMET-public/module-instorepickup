<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use MagentoEse\InStorePickup\Model\Resource\StoreLocation\CollectionFactory;

class Index extends Action
{
    /**
     * Used for calculating distance from point in radius search
     */
    const DISTANCE = 50;

    /**
     * Maximum number of records to return in result set
     */
    const LIMIT_RESULTS = 20;

    /**
     * @var CollectionFactory
     */
    private $storeLocColFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @param Context $context
     * @param Data $jsonHelper
     * @param CollectionFactory $storeLocColFactory
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        CollectionFactory $storeLocColFactory
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

        // Initialize the store location collection
        /** @var $storeLocCol \MagentoEse\InStorePickup\Model\Resource\StoreLocation\Collection */
        $storeLocCol = $this->storeLocColFactory->create();
        $storeLocCol->addZipcodeDistanceFilter($zipcode, $this::DISTANCE);
        $storeLocCol->setPageSize($this::LIMIT_RESULTS);
        $storeLocCol->addOrder($storeLocCol::DISTANCE_COLUMN, $storeLocCol::SORT_ORDER_ASC);

        // Build the response data set
        $response = [];
        $response['distance'] = $this::DISTANCE;
        $response['zipcode'] = $zipcode;
        foreach ($storeLocCol as $storeLoc) {
            $response['stores'][] = [
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
}
