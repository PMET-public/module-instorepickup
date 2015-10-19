<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Controller\ResultFactory;
use MagentoEse\InStorePickup\Model\StoreLocationFactory;
use MagentoEse\InStorePickup\Model\Session;
use Magento\Checkout\Model\Session as CheckoutSession;

class Selection extends Action
{
    /**
     * Checkout session
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

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
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        StoreLocationFactory $storeLocationFactory,
        Session\StoreLocation $storeLocationSession,
        CheckoutSession $checkoutSession
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->storeLocationFactory = $storeLocationFactory;
        $this->storeLocationSession = $storeLocationSession;
        $this->checkoutSession = $checkoutSession;
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

            // Check if we need to update the StoreLocationId in the Quote
            if ($this->checkoutSession->getQuote() && $this->checkoutSession->getQuote()->getInstorepickupStoreLocationId() > 0) {
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationId($storeLocation->getId());
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationName($storeLocation->getName());
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationCity($storeLocation->getCity());
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationState($storeLocation->getState());
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationPostalCode($storeLocation->getPostalCode());
                $this->checkoutSession->getQuote()->setInstorepickupStoreLocationPhone($storeLocation->getPhone());
                $this->checkoutSession->getQuote()->save();
            }

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
                $this->messageManager->addExceptionMessage($e, __('Error getting store location details for navigation dropdown.'));
            }

            // Get HTML to update store pdp detail
            try {
                /** @var \Magento\Framework\View\Result\Page $pdpResult */
                $pdpResult = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
                $pdpLayout = $pdpResult->addHandle('catalog_product_view')->getLayout();
                $pdpBlock = $pdpLayout->getBlock('product.info.instorepickup.options');
                $response['productInfoInstorepickupOptions'] = $pdpBlock->toHtml();
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Error getting store location details for product details.'));
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
