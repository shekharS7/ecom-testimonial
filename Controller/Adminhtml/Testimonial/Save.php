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
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use RicherIndex\Testimonial\Model\TestimonialFactory;
use RicherIndex\Testimonial\Model\Api\TestimonialRepository;
use RicherIndex\Testimonial\Model\ImageUploader;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var TestimonialRepository
     */
    protected $testimonialRepository;

    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     * @param TestimonialRepository $testimonialRepository
     */
    public function __construct(
        Action\Context $context,
        TestimonialFactory $testimonialFactory,
        DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader,
        TestimonialRepository $testimonialRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->testimonialFactory = $testimonialFactory;
        $this->imageUploader = $imageUploader;
        $this->testimonialRepository = $testimonialRepository;
        parent::__construct($context);
    }
    
    
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RicherIndex_Testimonial::testimonial_save');
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $model = $this->testimonialFactory->create();
            try {
                if ($id = (int) $this->getRequest()->getParam('testimonial_id')) {
                    $model = $this->testimonialRepository->getById($id);
                    if ($id != $model->getTestimonialId()) {
                        $this->messageManager->addErrorMessage(__('This Testimonial no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                $data = $this->_filterTestimonialData($data);
                $model->addData($data);
                $this->testimonialRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the Testimonial'));
                $this->dataPersistor->clear('richerindex_testimonial');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Testimonial.')
                );
            }

            $this->dataPersistor->set('richerindex_testimonial', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'testimonial_id' => $this->getRequest()->getParam('testimonial_id')
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Filter faq group data
     *
     * @param array $rawData
     * @return array
     */
    protected function _filterTestimonialData(array $rawData)
    {
        $data = $rawData;
        if (isset($data['profile_pic'][0]['name'])) {
            $data['profile_pic'] = $data['profile_pic'][0]['name'];
        } else {
            $data['profile_pic'] = null;
        }
         
        $stores = $data['storeview'];
        if (isset($stores)) {
            $store = implode(',', $data['storeview']);
            $data['storeview'] = $store;
        }

        return $data;
    }
}
