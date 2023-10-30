<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_Testimonial
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\Testimonial\Controller\Adminhtml;

use MageINIC\Testimonial\Api\Data\TestimonialInterfaceFactory as TestimonialFactory;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use MageINIC\Testimonial\Model\ImageUploader;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Request\DataPersistorInterface as DataPersistor;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class for Testimonial
 */
abstract class Testimonial extends Action
{
    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $resultForwardFactory;

    /**
     * @var TestimonialFactory
     */
    protected TestimonialFactory $testimonialFactory;

    /**
     * @var TestimonialRepository
     */
    protected TestimonialRepository $testimonialRepository;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var DataPersistor
     */
    protected DataPersistor $dataPersistor;

    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploader;

    /**
     * @var Filter
     */
    protected Filter $filter;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Testimonial Constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param TestimonialFactory $testimonialFactory
     * @param TestimonialRepository $testimonialRepository
     * @param CollectionFactory $collectionFactory
     * @param DataPersistor $dataPersistor
     * @param ImageUploader $imageUploader
     * @param Filter $filter
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context               $context,
        Registry              $coreRegistry,
        PageFactory           $resultPageFactory,
        ForwardFactory        $resultForwardFactory,
        TestimonialFactory    $testimonialFactory,
        TestimonialRepository $testimonialRepository,
        CollectionFactory     $collectionFactory,
        DataPersistor         $dataPersistor,
        ImageUploader         $imageUploader,
        Filter                $filter,
        LoggerInterface       $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialRepository = $testimonialRepository;
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
        $this->filter = $filter;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('MageINIC'), __('MageINIC'))
            ->addBreadcrumb(__('Testimonial'), __('Testimonial'));
        return $resultPage;
    }
}
