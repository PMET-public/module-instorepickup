<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;
use Magento\Framework\Controller\ResultFactory;

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

        // Respond back with list of params originally sent
        $response = [];
        $response['params'] = $params;

        // Load the store location based on the ID sent in the params
        $this->storeLocation->load($params['store-id']);
        if ($this->storeLocation != null) {

            // Set the new cookie value
            $this->storeLocationCookieManager->setStoreCookie($this->storeLocation);

            // respond with selected store details
            $response['storeName'] = $this->storeLocation->getName();

            // Get HTML to update store detail dropdown box
            try {
                /** @var \Magento\Framework\View\Result\Page $response */
                $detailResult = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
                $detailLayout = $detailResult->addHandle('instorepickup_storesearch_detail')->getLayout();
                $detailBlock = $detailLayout->getBlock('magentoese_instorepickup_storedetails');
                $response['storeDetailHtml'] = $detailBlock->toHtml();
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Error getting store location details.'));
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
