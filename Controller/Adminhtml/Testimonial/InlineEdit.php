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

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use RicherIndex\Testimonial\Model\Api\TestimonialRepository;

class InlineEdit extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var TestimonialRepository
     */
    protected $testimonialRepository;

    /**
     * InlineEdit constructor.
     * @param Action\Context $context
     * @param JsonFactory $jsonFactory
     * @param TestimonialRepository $testimonialRepository
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $jsonFactory,
        TestimonialRepository $testimonialRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->testimonialRepository = $testimonialRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('RicherIndex_Testimonial::testimonial_edit');
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (empty($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $testimonialId) {
                    try {
                        $model = $this->testimonialRepository->getById($testimonialId);
                        $model->setData(array_merge($model->getData(), $postItems[$testimonialId]));
                        $this->testimonialRepository->save($model);
                    } catch (\Exception $e) {
                        $messages[] = "[Testimonial ID: {$testimonialId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}