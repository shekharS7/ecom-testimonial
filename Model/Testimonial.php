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

namespace RicherIndex\Testimonial\Model;
use RicherIndex\Testimonial\Api\Data\TestimonialInterface;
use RicherIndex\Testimonial\Api\Data\TestimonialInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Testimonial extends \Magento\Framework\Model\AbstractModel implements TestimonialInterface
{
    /**
     * testimonial's Statuses
     */
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;

    /**
     *
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    protected $_testimonialHelper;

    protected $_resource;
   
    protected $testimonialDataFactory;
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'testimonial';

    /**
     * @var string
     */
    protected $_cacheTag = 'testimonial';

    protected $dataObjectHelper;
    /**
     * Testimonial constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Testimonial|null $resource
     * @param ResourceModel\Testimonial\Collection|null $resourceCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $url
     * @param \RicherIndex\Testimonial\Helper\Data $testimonialHelper
     * @param TestimonialInterfaceFactory $testimonialDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \RicherIndex\Testimonial\Model\ResourceModel\Testimonial $resource = null,
        \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\Collection $resourceCollection = null,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $url,
        TestimonialInterfaceFactory $testimonialDataFactory,
        DataObjectHelper $dataObjectHelper,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_url          = $url;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_resource          = $resource;
        $this->testimonialDataFactory = $testimonialDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;

    }//end __construct()


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('RicherIndex\Testimonial\Model\ResourceModel\Testimonial');

    }



    /**
     * Prepare page's statuses.
     * Available event cms_page_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
                self::STATUS_ENABLED  => __('Enabled'),
                self::STATUS_DISABLED => __('Disabled'),
               ];

    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * {@inheritdoc}
     */
    public function getTestimonialId(){
        return $this->getData(self::TESTIMONIAL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setTestimonialId($value){
        return $this->setData(self::TESTIMONIAL_ID, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(){
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($value){
        return $this->setData(self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getProfilePic(){
        return $this->getData(self::PROFILE_PIC);
    }

    /**
     * {@inheritdoc}
     */
    public function setProfilePic($value){
        return $this->setData(self::PROFILE_PIC, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getCompanyName(){
        return $this->getData(self::COMPANY_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setCompanyName($value){
        return $this->setData(self::COMPANY_NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(){
        return $this->getData(self::MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($value){
        return $this->setData(self::MESSAGE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPost(){
        return $this->getData(self::POST);
    }

    /**
     * {@inheritdoc}
     */
    public function setPost($value){
        return $this->setData(self::POST, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
	
	/**
     * {@inheritdoc}
     */
    public function setCreatedAt($value){
        return $this->setData(self::CREATED_AT, $value);
    }
    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($value){
        return $this->setData(self::UPDATED_AT, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(){
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($value){
        return $this->setData(self::STATUS, $value); 
    }
	
    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getDataExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \RicherIndex\Testimonial\Api\Data\TestimonialExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}