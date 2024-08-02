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

interface TestimonialInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const TESTIMONIAL_ID = 'testimonial_id';
    const NAME = 'name';
    const PROFILE_PIC = 'profile_pic';
    const COMPANY_NAME = 'company_name';
    const MESSAGE = 'message';
    const POST = 'post';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const STATUS = 'status';
    /**
     * Get value
     * @return int|null
     */
    public function getTestimonialId();

    /**
     * Set value
     * @param int|null $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setTestimonialId($value);

    /**
     * Get value
     * @return string
     */
    public function getName();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setName($value);

    /**
     * Get value
     * @return string
     */
    public function getCompanyName();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setCompanyName($value);

    /**
     * Get value
     * @return string
     */
    public function getProfilePic();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setProfilePic($value);

    /**
     * Get value
     * @return string
     */
    public function getMessage();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setMessage($value);

    /**
     * Get value
     * @return string
     */
    public function getPost();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setPost($value);

    /**
     * Get value
     * @return int
     */
    public function getStatus();

    /**
     * Set value
     * @param int $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setStatus($value);

	/**
     * Get value
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setCreatedAt($value);

    /**
     * Get value
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set value
     * @param string $value
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialInterface
     */
    public function setUpdatedAt($value);
 
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \RicherIndex\Testimonial\Api\Data\TestimonialExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \RicherIndex\Testimonial\Api\Data\TestimonialExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \RicherIndex\Testimonial\Api\Data\TestimonialExtensionInterface $extensionAttributes
    );
}
