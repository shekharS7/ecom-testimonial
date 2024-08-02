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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{

	/**
     * @var \Magento\Framework\App\Request\Http
	 */
    protected $httpRequest;
     /**
     * @var \RicherIndex\Testimonial\Model\TestimonialFactory
     */
    protected $testimonialFactory;

     /**
     * @var \RicherIndex\Testimonial\Helper\Data
     */
    protected $helper;


    public function __construct(
        Action\Context $context,	
		\Magento\Framework\App\Request\Http $httpRequest,
        \RicherIndex\Testimonial\Model\TestimonialFactory $testimonialFactory,
		\RicherIndex\Testimonial\Helper\Data $helper

    ){
		$this->httpRequest  = $httpRequest;
        $this->testimonialFactory = $testimonialFactory;
        $this->helper = $helper;
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
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \RicherIndex\Testimonial\Model\Testimonial $model */
            $model = $this->testimonialFactory->create();

            $id = $this->getRequest()->getParam('testimonial_id');
            if ($id) {
                $model->load($id);
            }
			/**
            * @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory

            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);
            $mediaFolder    = 'kiwi/testimonial/';
            $path           = $mediaDirectory->getAbsolutePath($mediaFolder);
            */
           /*// Delete, Upload Image
            $imagePath = $mediaDirectory->getAbsolutePath($model->getProfilePic());
            if(isset($data['profile_pic']['delete']) && file_exists($imagePath)) {
                unlink($imagePath);
                $data['profile_pic'] = '';
            }

            if(isset($data['profile_pic']) && is_array($data['profile_pic'])) {
                unset($data['profile_pic']);
            }
			if($image = $this->uploadImage('profile_pic')) {
                $data['profile_pic'] = $image;
            }*/
            $imageRequest = $this->getRequest()->getFiles('profile_pic');
            $data = $this->helper->imageUpload($data, $imageRequest);                      
            $model->setData($data);

            $this->_eventManager->dispatch(
                'testimonial_prepare_save',
                ['testimonial' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Testimonial.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the testimonial.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $this->getRequest()->getParam('testimonial_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
	
	 public function uploadImage($fieldId = 'profile_pic')
    {
        $image = $this->httpRequest->getFiles($fieldId);
        if (isset($image['error']) && $image['error'] == 0) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $uploader       = $this->_objectManager->create(
                'Magento\Framework\File\Uploader',
                array('fileId' => $fieldId)
            );
            if ($uploader) {
                $uploader = $this->_objectManager->create(
                    'Magento\Framework\File\Uploader',
                    array('fileId' => $fieldId)
                );
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                $mediaFolder    = 'testimonial/tmp/profile/';
                try {
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($mediaFolder)
                    );
                    return $mediaFolder.$result['name'];
                } catch (\Exception $e) {
                    $this->_logger->critical($e);
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $this->getRequest()->getParam('testimonial_id')]);
                }
            }//end if
        }
        return;

    }//end uploadImage()
}