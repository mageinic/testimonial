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

namespace MageINIC\Testimonial\Block;

use Exception;
use MageINIC\Testimonial\Model\Testimonial\Image;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface as SearchCriteria;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;

/**
 * Class for Grid
 */
class Grid extends Template
{
    public const EDIT_URL = 'testimonial/testimonial/form';

    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var TestimonialRepository
     */
    private TestimonialRepository $dataModel;

    /**
     * @var SearchCriteria
     */
    private SearchCriteria $searchCriteria;

    /**
     * @var FilterGroup
     */
    private FilterGroup $filterGroup;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @var SessionFactory
     */
    private SessionFactory $session;

    /**
     * @var Image
     */
    protected Image $testimonialImage;

    /**
     * Grid Constructor.
     *
     * @param Context $context
     * @param SearchCriteria $criteria
     * @param TestimonialRepository $dataModel
     * @param FilterGroup $filterGroup
     * @param FilterBuilder $filterBuilder
     * @param SessionFactory $session
     * @param ManagerInterface $messageManager
     * @param Image $testimonialImage
     * @param array $data
     */
    public function __construct(
        Context               $context,
        SearchCriteria        $criteria,
        TestimonialRepository $dataModel,
        FilterGroup           $filterGroup,
        FilterBuilder         $filterBuilder,
        SessionFactory        $session,
        ManagerInterface      $messageManager,
        Image                 $testimonialImage,
        array                 $data = []
    ) {
        $this->searchCriteria = $criteria;
        $this->dataModel = $dataModel;
        $this->filterGroup = $filterGroup;
        $this->filterBuilder = $filterBuilder;
        $this->session = $session;
        $this->messageManager = $messageManager;
        $this->testimonialImage = $testimonialImage;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Testimonial Collection
     *
     * @return TestimonialInterface[]|void
     */
    public function getTestimonialCollection()
    {
        try {
            $this->filterGroup->setFilters([
                $this->filterBuilder
                    ->setField(TestimonialInterface::EMAIL)
                    ->setConditionType('eq')
                    ->setValue($this->session->create()->getCustomer()->getEmail())
                    ->create(),
            ]);
            $this->searchCriteria->setFilterGroups([$this->filterGroup]);
            $collection = $this->dataModel->getList($this->searchCriteria);
            return $collection->getItems();
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * Retrieve Edit Url
     *
     * @param int $testimonialId
     * @return string
     */
    public function getEditUrl(int $testimonialId): string
    {
        return $this->getUrl(self::EDIT_URL, ['id' => $testimonialId]);
    }

    /**
     * Retrieve Rating Start Percentage
     *
     * @param int $rating
     * @return int
     */
    public function getRatingStartPercentage(int $rating): int
    {
        return match ($rating) {
            1 => 20,
            2 => 40,
            3 => 60,
            4 => 80,
            5 => 100,
            default => 0,
        };
    }

    /**
     * Retrieve Image Url
     *
     * @param TestimonialInterface $testimonial
     * @return string
     * @throws LocalizedException
     */
    public function getImageUrl(TestimonialInterface $testimonial): string
    {
        return $this->testimonialImage->getUrl($testimonial);
    }
}
