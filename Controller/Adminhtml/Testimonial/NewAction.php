<?php
/**
 * Copyright © Shekhar Suman, 2024. All rights reserved.
 * See COPYING.txt for license details.
 * 
 * @package     RicherIndex_Testimonial
 * @version     1.0.0
 * @license     MIT License (http://opensource.org/licenses/MIT)
 * @autor       Shekhar Suman
 */
namespace RicherIndex\Testimonial\Controller\Adminhtml\Testimonial;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;


    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);

    }//end __construct()


    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RicherIndex_Testimonial::testimonial_edit');

    }//end _isAllowed()


    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /*
            *
            *
            * @var \Magento\Backend\Model\View\Result\Forward $resultForward
        */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');

    }


}