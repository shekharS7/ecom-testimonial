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
namespace RicherIndex\Testimonial\Model\Resolver;

use Magento\Framework\Data\Collection as AbstractDbCollection;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use RicherIndex\Testimonial\Model\Config\DefaultConfig;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory as TestimonialCollectionFactory;

class Testimonial implements ResolverInterface
{
    /**
     * @var TestimonialCollectionFactory
     */
    protected $testimonialCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * FaqGroup constructor.
     * @param TestimonialCollectionFactory $testimonialCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        TestimonialCollectionFactory $testimonialCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->testimonialCollectionFactory = $testimonialCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }

        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        /** @var \RicherIndex\Testimonial\Model\ResourceModel\Testimonial\Collection $testimonialCollection */
        $testimonialCollection = $this->testimonialCollectionFactory->create();
        $testimonialCollection->addFieldToFilter('status', 1);
        $testimonialCollection->setOrder('testimonial_id', AbstractDbCollection::SORT_ORDER_ASC);

        if (isset($args['pageSize'])) {
            $testimonialCollection->setPageSize($args['pageSize']);
        }

        if (isset($args['currentPage'])) {
            $testimonialCollection->setCurPage($args['currentPage']);
        }

        $groups = $testimonialCollection->getData();
        foreach ($groups as $key => $group) {
            if ($profile = $group['profile_pic']) {
                $groups[$key]['profile_pic'] = $this->getProfilePic($profile);
            }
        }

        return $groups;
    }

    /**
     * Retrieve testimonial profile url
     *
     * @param string $profile
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProfilePic($profile)
    {
        $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . DefaultConfig::PROFILE_TMP_PATH . $profile;
    }
}
