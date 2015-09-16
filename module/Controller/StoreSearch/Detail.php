<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;
use Magento\Framework\Controller\ResultFactory;

class Detail extends Action
{
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
     * @param StoreLocationCookieManager $storeLocationCookieManager
     * @param StoreLocation $storeLocation
     */
    public function __construct(
        Context $context,
        StoreLocationCookieManager $storeLocationCookieManager,
        StoreLocation $storeLocation
    ) {
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
        // check for existing store
        $currentStoreLocationId = $this->storeLocationCookieManager->getStoreLocationIdFromCookie();

        $this->storeLocation->load($currentStoreLocationId);
        if ($this->storeLocation != null) {

            // Get StoreDetail block and render only it's contents
        } else {
            // Send back no store html
        }



        try {
            /** @var \Magento\Framework\View\Result\Page $response */
            $response = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $layout = $response->addHandle('instorepickup_storesearch_detail')->getLayout();

            $response = $layout->getBlock('magentoese_instorepickup_storedetails')->toHtml();
            $this->getResponse()->setBody($response);
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t update shipping method.'));
        }



        // Bypass post the dispatch events from the app action
        //$this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
    }
}