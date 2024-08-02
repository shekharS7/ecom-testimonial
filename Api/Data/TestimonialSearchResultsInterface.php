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
namespace RicherIndex\Testimonial\Api\Data;

interface TestimonialSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Testimonial list.
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface[]
     */
    public function getItems();

    /**
     * Set Testimonial list.
     * @param \RicherIndex\Testimonial\Api\Data\TestimonialInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}