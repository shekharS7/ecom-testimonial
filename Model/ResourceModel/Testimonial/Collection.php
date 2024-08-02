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
namespace RicherIndex\Testimonial\Model\ResourceModel\Testimonial;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'testimonial_id';


    protected function _construct()
    {
        $this->_init('RicherIndex\Testimonial\Model\Testimonial', 'RicherIndex\Testimonial\Model\ResourceModel\Testimonial');
    }

}
