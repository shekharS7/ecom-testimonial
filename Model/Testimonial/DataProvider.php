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
namespace RicherIndex\Testimonial\Model\Testimonial;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var CollectionFactory
     */
    public $collection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $this->loadedData[$model->getTestimonialId()] = $model->getData();
            if ($model->getProfilePic()) {
                $m['profile_pic'][0]['name'] = $model->getIcon();
                $m['profile_pic'][0]['url'] = $this->getMediaUrl() . $model->getProfilePic();
                $fullData = $this->loadedData;
                $this->loadedData[$model->getTestimonialId()] = array_merge($fullData[$model->getTestimonialId()], $m);
            }
        }

        $data = $this->dataPersistor->get('richerindex_testimonial');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getTestimonialId()] = $model->getData();
            $this->dataPersistor->clear('richerindex_testimonial');
        }

        return $this->loadedData;
    }

    /**
     * Get media url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'testimonial/tmp/profile/';
        return $mediaUrl;
    }
}
