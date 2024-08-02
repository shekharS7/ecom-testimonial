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
namespace RicherIndex\Testimonial\Block;

use RicherIndex\Testimonial\Api\Data\TestimonialInterface;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial\Collection as TestimonialCollection;

class TestimonialsList extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory
     */
    protected $_testimonialCollectionFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_testimonialCollectionFactory = $testimonialCollectionFactory;
    }

    /**
     * @return \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\Collection
     */
    public function getTestimonials()
    {
        if (!$this->hasData('testimonials')) {
            $testimonials = $this->_testimonialCollectionFactory
                ->create()
                ->addFilter('status', 1)
                ->addOrder(
                    TestimonialInterface::CREATED_AT,
                    TestimonialCollection::SORT_ORDER_DESC
                );
            $this->setData('testimonials', $testimonials);
        }
        return $this->getData('testimonials');
    }
    
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getTestimonials()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'testimonials.list.pager'
            )->setCollection(
                $this->getTestimonials()
            );
            $this->setChild('pager', $pager);
            $this->getTestimonials()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\RicherIndex\Testimonial\Model\Testimonial::CACHE_TAG . '_' . 'list'];
    }

}