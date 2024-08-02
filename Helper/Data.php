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

namespace RicherIndex\Testimonial\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Model\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var string
     */
    const MEDIA_PATH = 'testimonial/tmp/profile';

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_adapterFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->_storeManager = $storeManager;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_adapterFactory = $adapterFactory;
        $this->messageManager = $messageManager;
        $this->_objectManager = $objectManager;
        $this->httpContext = $httpContext;
        parent::__construct($context);
    }

    /**
     * @return mixed|null
     */
    public function getCustomerGroup()
    {
        return $this->httpContext->getValue(Context::CONTEXT_GROUP);
    }

    /**
     * @return bool
     */
    public function isAllowedToAddTestimonial()
    {
        $allowCustomersGroup = explode(",", $this->getAllowedCustomerGroups());
        if (in_array($this->getCustomerGroup(), $allowCustomersGroup)) {
            return true;
        }
        return false;
    }

    /**
     * Is extention enabled
     *
     * @return boolean
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/is_active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * Check is FontAwesome enabled
     *
     * @return boolean
     */
    public function isFontAwesomeEnabled()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * Check is Google reCAPTCHA enabled
     *
     * @return boolean
     */
    public function isRecaptchaEnabled()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/is_enabled_recaptcha',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * @return string
     */
    public function getRecaptchaSiteKey()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/app_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * Retrieve canUseCdn flag
     *
     * @return boolean
     */
    public function canUseCdn()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/use_cdn',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * @return string
     */
    public function getAllowedCustomerGroups()
    {
        return $this->scopeConfig->getValue(
            'richerindex_testimonial/general/customers_groups',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }

    /**
     * @param array $data
     * @param array $imageRequest
     * @return array
     */
    public function imageUpload($data, $imageRequest)
    {
        if ($imageRequest) {
            if (isset($imageRequest['name'])) {
                $fileName = $imageRequest['name'];
            } else {
                $fileName = '';
            }
        } else {
            $fileName = '';
        }

        if ($imageRequest && strlen($fileName)) {
            try {
                $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);

                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);

                /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                $imageAdapter = $this->_adapterFactory->create();

                $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);

                /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath(self::MEDIA_PATH)
                );
                $data['image'] = $result['file'];
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        } else {
            if (isset($data['image']) && isset($data['image']['value'])) {
                if (isset($data['image']['delete'])) {
                    $data['image'] = null;
                    $data['delete_image'] = true;
                } elseif (isset($data['image']['value'])) {
                    $data['image'] = str_replace(self::MEDIA_PATH, '', $data['image']['value']);
                } else {
                    $data['image'] = null;
                }
            }
        }
        return $data;
    }
}
