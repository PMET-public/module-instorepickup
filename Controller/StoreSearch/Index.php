<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use MagentoEse\InStorePickup\Model\ResourceModel\StoreLocation\CollectionFactory;
use MagentoEse\InStorePickup\Model\ResourceModel\StoreLocation;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

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
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SourceItemRepositoryInterface
     */
    private $sourceItemRepository;

    /** @var CatalogSession  */
    private $catalogSession;

    /** @var ProductRepositoryInterface  */
    private $productRepository;

    /** @var ScopeConfigInterface  */
    private $config;

    /**
     * Index constructor.
     * @param Context $context
     * @param Data $jsonHelper
     * @param CollectionFactory $storeLocColFactory
     * @param CatalogSession $catalogSession
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SourceItemRepositoryInterface $sourceItemRepository
     * @param ProductRepositoryInterface $productRepository
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        CollectionFactory $storeLocColFactory,
        CatalogSession $catalogSession,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SourceItemRepositoryInterface $sourceItemRepository,
        ProductRepositoryInterface $productRepository,
        ScopeConfigInterface $config

    ) {
        $this->jsonHelper = $jsonHelper;
        $this->storeLocColFactory = $storeLocColFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sourceItemRepository = $sourceItemRepository;
        $this->catalogSession = $catalogSession;
        $this->productRepository = $productRepository;
        $this->config = $config;
        parent::__construct($context);
    }

    public function getSourceItemDetailBySKU($sku,$sourceCode)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(SourceItemInterface::SKU, $sku)
            ->addFilter(SourceItemInterface::SOURCE_CODE,$sourceCode)
            //->addFilter(SourceItemInterface::STATUS_IN_STOCK,1)
            ->create();

        $result = $this->sourceItemRepository->getList($searchCriteria)->getItems();
        foreach ($result as $item) {
            return $item->getData();
            break;
        }
    }


    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        $params = (array)$this->getRequest()->getParams();
        try {
            $product = $this->productRepository->getById($params['productId']);
            $sku = $product->getSku();
        }catch(\Magento\Framework\Exception\NoSuchEntityException $exception){
            $sku='0';
        }


        // Define parameters for looking up available stores
        $zipcode = $params['searchCriteria'];

        // Initialize the store location collection
        /** @var $storeLocCol \MagentoEse\InStorePickup\Model\ResourceModel\StoreLocation\Collection */
        $storeLocCol = $this->storeLocColFactory->create();
        $storeLocCol->addZipcodeDistanceFilter($zipcode, $this::DISTANCE);
        $storeLocCol->setPageSize($this::LIMIT_RESULTS);
        $storeLocCol->addOrder($storeLocCol::DISTANCE_COLUMN, $storeLocCol::SORT_ORDER_ASC);
        $storeLocCol->addFieldToFilter('is_pickup_location_active',1);

        // Build the response data set
        $response = [];
        $response['distance'] = $this->config->getValue('carriers/instore/search_radius','default');
        $response['zipcode'] = $zipcode;
        foreach ($storeLocCol as $storeLoc) {
            //if the product is configurable, get the sku of the first product with inventory
            if(isset($product) && $product->getTypeId()=='configurable'){
                $children = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($children as $child){
                    if($this->getSourceItemDetailBySKU($child->getSku(),$storeLoc->getId())['quantity'] >0){
                        $sku = $child->getSku();
                        break;
                    }
                }
            }
            /** @var $storeLoc StoreLocation */
            $f = intval($this->getSourceItemDetailBySKU($sku,$storeLoc->getId())['quantity']);
            if(intval($this->getSourceItemDetailBySKU($sku,$storeLoc->getId())['quantity']) > 0){
                $response['stores'][] = [
                    'id' => $storeLoc->getId(),
                    'name' => $storeLoc->getName(),
                    'street_address' => $storeLoc->getStreet(),
                    'city' => $storeLoc->getCity(),
                    'state' => $storeLoc->getRegion(),
                    'postal_code' => $storeLoc->getPostcode(),
                    'phone' => $storeLoc->getPhone(),
                    'distance' => $storeLoc->getDistance(),
                    //'inventory' => $storeLoc->getInventory()
                    'inventory' => intval($this->getSourceItemDetailBySKU($sku,$storeLoc->getId())['quantity'])
                ];
            }

        }

        // Represent the response as JSON and encode the response object as a JSON data set
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );

        // Bypass post the dispatch events from the app action
        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
    }
}
