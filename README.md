# Module RicherIndex Testimonial


### Type 1: Zip file

 - Unzip the zip file in `app/code/RicherIndex`
 - Enable the module by running `php bin/magento module:enable RicherIndex_Testimonial`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Compile by running `php bin/magento setup:di:compile`
 - Flush the cache by running `php bin/magento cache:flush`
 - Export Testimonial CSV by running `php bin/magento testimonial:export:csv`


## Configuration
   

## Specifications
    RicherIndex-> Testimonial
    Testimonials UI grid will display the list of all the Testimonial



## Attributes
 - Table - richerindex_testimonial (following attributes)
 - testimonial_id
 - company_name
 - name
 - message
 - post
 - profile_pic
 - status
 - created_at
 - updated_at


## NOTE:
I encountered some challenges with my laptop as it is not compatible for Magento2 development , 
so I had to complete all tasks on a cloud server. Unfortunately, this meant that I was unable to perform the 
phpcodesniffer test and some unit tests due to issues such as "Config file allure/allure.config.php doesn't exist."
    Magento Version :  "2.4.6-p3"
	Php Version: 8.1.26
	
## Task having Magor issues that need improvement 
1. Profile pic uploader is missing in form page. (Due to time constraints not able to complete)

## Screenshot
    Images include inside module folder(Report)
