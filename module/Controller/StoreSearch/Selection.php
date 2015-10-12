<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Controller\ResultFactory;
use MagentoEse\InStorePickup\Model\StoreLocationFactory;
use MagentoEse\InStorePickup\Model\Session;

class Selection extends Action
{
    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * Store Location Factory
     *
     * @var StoreLocationFactory
     */
    protected $storeLocationFactory;

    /**
     * Store Location session
     *
     * @var Session\StoreLocation
     */
    protected $storeLocationSession;

    /**
     * @param Context $context
     * @param Data $jsonHelper
     * @param StoreLocationFactory $storeLocationFactory
     * @param Session\StoreLocation $storeLocationSession
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        StoreLocationFactory $storeLocationFactory,
        Session\StoreLocation $storeLocationSession
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->storeLocationFactory = $storeLocationFactory;
        $this->storeLocationSession = $storeLocationSession;
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
        $storeLocation = $this->storeLocationFactory->create();
        $storeLocation->load($params['store-id']);
        if ($storeLocation->getId() > 0) {

            // Save the Store Location selection to the session
            $this->storeLocationSession->setStoreLocation($storeLocation);

            // respond with selected store details
            $response['storeName'] = $storeLocation->getName();

            // Get HTML to update store detail dropdown box
            try {
                /** @var \Magento\Framework\View\Result\Page $detailResult */
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
