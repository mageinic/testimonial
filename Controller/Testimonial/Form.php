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

namespace MageINIC\Testimonial\Controller\Testimonial;

use MageINIC\Testimonial\Api\Data\TestimonialInterfaceFactory as TestimonialFactory;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use MageINIC\Testimonial\Helper\Data;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Class for Form
 */
class Form extends Action implements HttpGetActionInterface
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var TestimonialFactory
     */
    private TestimonialFactory $testimonialFactory;

    /**
     * @var Data
     */
    private Data $helperData;

    /**
     * @var TestimonialRepository
     */
    private TestimonialRepository $testimonialRepository;

    /**
     * Form Constructor.
     *
     * @param Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param TestimonialRepository $testimonialRepository
     * @param Registry $registry
     * @param SessionFactory $session
     * @param Data $helperData
     */
    public function __construct(
        Context               $context,
        TestimonialFactory    $testimonialFactory,
        TestimonialRepository $testimonialRepository,
        Registry              $registry,
        SessionFactory        $session,
        Data                  $helperData
    ) {
        $this->session = $session->create();
        $this->registry = $registry;
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialRepository = $testimonialRepository;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResponseInterface|void
     * @throws LocalizedException
     */
    public function execute()
    {
        if (!$this->session->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        }

        if (!$this->helperData->isForm()) {
            $this->_forward('noroute');
        } else {
            $this->_view->loadLayout();
            $id = $this->getRequest()->getParam('id');
            $model = $id ? $this->testimonialRepository->getById($id) : $this->testimonialFactory->create();
            if ($model->getId() && $this->session->getCustomer()->getEmail() == $model->getEmail()) {
                $this->registry->register('current_testimonial', $model);
            }
            $this->_view->getPage()->getConfig()->getTitle()->set(
                $id ? __('Testimonial: [%1]', $model->getTitle()) : __('Add Testimonial')
            );
            $this->_view->renderLayout();
        }
    }
}
