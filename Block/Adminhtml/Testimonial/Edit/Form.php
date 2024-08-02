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

namespace RicherIndex\Testimonial\Block\Adminhtml\Testimonial\Edit;

use RicherIndex\Testimonial\Helper\Data;

/**
 * Adminhtml testimonial edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    
    protected $profilePic;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('testimonial_form');
        $this->setTitle(__('Testimonial'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \RicherIndex\Testimonial\Model\Testimonial $model */
        $model = $this->_coreRegistry->registry('testimonial');
		if ($this->getRequest()->getParam('testimonial_id')) {
            if ($model->getProfilePic()) {
                $this->profilePic = Data::MEDIA_PATH . $model->getProfilePic();
            }
		}
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('data_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getTestimonialId()) {
            $fieldset->addField('testimonial_id', 'hidden', 
            [
                'name' => 'testimonial_id',
            'value' =>$model->getTestimonialId()
            ]
            );
        }

        	$fieldset->addField(
            'company_name',
            'text',
            [
                'label'  => __('Company Name'),
                'title'  => __('Company Name'),
                'name'   => 'company_name',
                'value' => $model->getCompanyName()
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'label'   => __('Name'),
                'title'   => __('Name'),
                'class'   => 'required-entry',
                'required'=> true,
                'name'    => 'name',
                'value' => $model->getName()
            ]
        );

        $fieldset->addField(
            'message',
            'text',
            [
                'label'  => __('Message'),
                'title'  => __('Message'),
                'name'   => 'message',
                'class'   => 'required-entry',
                'required'=> true,
                'value' => $model->getMessage()
            ]
        );

        $fieldset->addField(
            'post',
            'text',
            [
                'label'  => __('Post'),
                'title'  => __('Post'),
                'name'   => 'post',
				'class'   => 'required-entry',
                'required'=> true,
                'value' => $model->getPost()
            ]
        );

        $fieldset->addField(
            'profile_pic',
            'image',
            [
                'label'  => __('Profile Pic'),
                'title'  => __('Profile Pic'),
                'name'   => 'profile_pic',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                'value' => $this->profilePic
                ]
        );
		
		  $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
                'value' => $model->getStatus()
            ]
        );
        /* Comment setValues we have set value individual field 
        $form->setValues($model->getData());*/
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
