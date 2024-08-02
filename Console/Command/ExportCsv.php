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
namespace RicherIndex\Testimonial\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Console\Cli;
use Magento\Framework\Filesystem\Driver\File;
use RicherIndex\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory as TestimonialCollectionFactory;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCsv extends Command
{
    protected $appState;
    protected $testimonialCollectionFactory;
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;
    protected $_filesystem;

    public function __construct(
        State $appState,
        TestimonialCollectionFactory $testimonialCollectionFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->appState = $appState;
        $this->testimonialCollectionFactory = $testimonialCollectionFactory;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->_filesystem = $filesystem;
        $this->directoryList = $directoryList;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('testimonial:export:csv')
            ->setDescription('Export Testimonial to CSV file');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
        $this->appState->setAreaCode(Area::AREA_GLOBAL);

        $testimonialCollection = $this->testimonialCollectionFactory->create();
        $testimonialCollection->addFilter('status',1);


        // Write the CSV headers
        /** Add yout header name here */
        $header = ['testimonial_id',
            'company_name' ,
            'name',
            'message',
            'post',
            'profile_pic',
            'status',
            'created_at',
            'updated_at'
        ];
        $fileName = 'testimonial.csv';
        $filePath = 'export/' . $fileName;
        $directory = $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $stream = $directory->openFile($filePath, 'w+');
                $stream->lock();

        $stream->writeCsv($header);

        foreach ($testimonialCollection as $testimonial) {
            
             $content = [];
               $content[] = $testimonial->getTestimonialId();
               $content[] = $testimonial->getCompanyName();
                $content[]= $testimonial->getName();
                 $content[]=$testimonial->getPost();
                $content[]= $testimonial->getMessage();
                 $content[]=$testimonial->getProfilePic();
                 $content[]=$testimonial->getStatus();
                 $content[]=$testimonial->getCreatedAt();
                $content[]= $testimonial->getUpdatedAt();
            
            $stream->writeCsv($content);
        }
    
        $stream->close();

 /*       $this->fileFactory->create(
            $fileName,
            [
                'type'  => "filename",
                'value' => $fileName,
                'rm'    => true, // True => File will be remove from directory after download.
            ],
            DirectoryList::VAR_DIR,
            'text/csv',
            null
        );
*/
       $output->writeln('<info>Testimonials have been exported to ' .DirectoryList::VAR_DIR.'/'.$filePath . '</info>');
        return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}
