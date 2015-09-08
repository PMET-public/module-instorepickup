<?php

namespace MagentoEse\InStorePickup\Controller\StoreSearch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Json\Helper\Data;

class Selection extends Action
{
    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @param Context $context
     * @param Data $jsonHelper
     */
    public function __construct(
        Context $context,
        Data $jsonHelper
    ) {
        $this->jsonHelper = $jsonHelper;
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

        $response = [
            'success' => true,
            'params' => $params
        ];

        // Represent the response as JSON and encode the response object as a JSON data set
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );

        // Bypass post the dispatch events from the app action
        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
    }
}