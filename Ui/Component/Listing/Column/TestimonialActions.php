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

namespace RicherIndex\Testimonial\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class TestimonialActions extends Column
{
    /** Url path */
    const TESTIMONIAL_PATH_EDIT = 'testimonials/testimonial/edit';
    const TESTIMONIAL_PATH_DELETE = 'testimonials/testimonial/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::TESTIMONIAL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['testimonial_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['testimonial_id' => $item['testimonial_id']]),
                        'label' => __('Edit')
                    ];
                   /* $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::TESTIMONIAL_PATH_DELETE, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                        ]
                    ];*/
                }
            }
        }

        return $dataSource;
    }
}