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
declare(strict_types=1);

namespace RicherIndex\Testimonial\Model\Api;

use RicherIndex\Testimonial\Api\TestimonialRepositoryInterface;
use RicherIndex\Testimonial\Api\Data\TestimonialInterfaceFactory;
use RicherIndex\Testimonial\Api\Data\TestimonialSearchResultsInterfaceFactory;
use RicherIndex\Testimonial\Model\TestimonialFactory;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial as ResourceTestimonial;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory as TestimonialCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class TestimonialRepository implements TestimonialRepositoryInterface
{

    protected $testimonialCollectionFactory;

    protected $dataObjectProcessor;

    protected $resource;

    protected $extensionAttributesJoinProcessor;

    protected $testimonialFactory;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    private $storeManager;

    protected $dataTestimonialFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;


    /**
     * @param ResourceTestimonial $resource
     * @param TestimonialFactory $testimonialFactory
     * @param TestimonialInterfaceFactory $dataTestimonialFactory
     * @param TestimonialCollectionFactory $testimonialCollectionFactory
     * @param TestimonialSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTestimonial $resource,
        TestimonialFactory $testimonialFactory,
        TestimonialInterfaceFactory $dataTestimonialFactory,
        TestimonialCollectionFactory $testimonialCollectionFactory,
        TestimonialSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialCollectionFactory = $testimonialCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTestimonialFactory = $dataTestimonialFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \RicherIndex\Testimonial\Api\Data\TestimonialInterface $testimonial
    ) {
        $testimonialData = $this->extensibleDataObjectConverter->toNestedArray(
            $testimonial,
            [],
            \RicherIndex\Testimonial\Api\Data\TestimonialInterface::class
        );
        $testimonialModel = $this->testimonialFactory->create()->setData($testimonialData);
        
        try {
            $this->resource->save($testimonialModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the testimonial: %1',
                $exception->getMessage()
            ));
        }
        return $testimonialModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $testimonial = $this->testimonialFactory->create();
        $this->resource->load($testimonial, $id);
        if (!$testimonial->getTestimonialId()) {
            throw new NoSuchEntityException(__('Testimonial with id "%1" does not exist.', $id));
        }
        return $testimonial;
    }


    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->testimonialCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \RicherIndex\Testimonial\Api\Data\TestimonialInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->testimonialCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \RicherIndex\Testimonial\Api\Data\TestimonialInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $collection->addFieldToFilter("status", 1);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \RicherIndex\Testimonial\Api\Data\TestimonialInterface $testimonial
    ) {
        try {
            $testimonialModel = $this->testimonialFactory->create();
            $this->resource->load($testimonialModel, $testimonial->getTestimonialId());
            $this->resource->delete($testimonialModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Testimonial: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($testimonialId)
    {
        return $this->delete($this->get($testimonialId));
    }
}
