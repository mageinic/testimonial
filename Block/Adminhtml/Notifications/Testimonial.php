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

namespace MageINIC\Testimonial\Block\Adminhtml\Notifications;

use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Adminhtml Block class Testimonial Notifications
 */
class Testimonial extends Template
{
    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var TestimonialRepository
     */
    protected TestimonialRepository $testimonialRepository;

    /**
     * Testimonial Constructor.
     *
     * @param Context $context
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TestimonialRepository $testimonialRepository
     * @param array $data
     */
    public function __construct(
        Context               $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TestimonialRepository $testimonialRepository,
        array                 $data = []
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->testimonialRepository = $testimonialRepository;
        parent::__construct($context, $data);
    }

    /**
     * Count Pending Testimonials.
     *
     * @return int|null
     * @throws LocalizedException
     */
    public function countPending(): ?int
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(TestimonialInterface::ENABLE, Status::PENDING)
            ->create();
        $message = $this->testimonialRepository->getList($searchCriteria);
        return count($message->getItems());
    }
}
