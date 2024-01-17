<?php
/**
 * MageINIC
 * Copyright (C) 2023. MageINIC <support@mageinic.com>
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
 * @package MageINIC_<ModuleName>
 * @copyright Copyright (c) 2023. MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\Testimonial\Block\Widget;

use Exception;
use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use MageINIC\Testimonial\Helper\Data;
use MageINIC\Testimonial\Model\Testimonial\Image;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Status;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Store;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Magento\Widget\Model\Template\FilterEmulate;

/**
 * Block Class for Testimonial widget
 */
class Testimonial extends Template implements BlockInterface
{
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var TestimonialRepository
     */
    private TestimonialRepository $testimonialRepository;

    /**
     * @var FilterEmulate
     */
    protected FilterEmulate $filterProvider;

    /**
     * @var Image
     */
    protected Image $testimonialImage;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * Testimonial Constructor.
     *
     * @param Context $context
     * @param Data $helper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TestimonialRepository $testimonialRepository
     * @param ManagerInterface $messageManager
     * @param FilterEmulate $filterProvider
     * @param Image $testimonialImage
     * @param Serializer $serializer
     * @param array $data
     */
    public function __construct(
        Context               $context,
        Data                  $helper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TestimonialRepository $testimonialRepository,
        ManagerInterface      $messageManager,
        FilterEmulate         $filterProvider,
        Image                 $testimonialImage,
        Serializer            $serializer,
        array                 $data = []
    ) {
        $this->helper = $helper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->testimonialRepository = $testimonialRepository;
        $this->messageManager = $messageManager;
        $this->filterProvider = $filterProvider;
        $this->testimonialImage = $testimonialImage;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Testimonial Collection
     *
     * @return TestimonialInterface[]|void
     */
    public function getTestimonial()
    {
        try {
            $currentStore = $this->helper->getCurrentStoreId();
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(TestimonialInterface::ENABLE, Status::ENABLED)
                ->addFilter(TestimonialInterface::STORE_ID, $currentStore)
                ->create();
            $collection = $this->testimonialRepository->getList($searchCriteria);

            return $collection->getItems();
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * Retrieve Enable Config
     *
     * @return bool
     */
    public function getEnableExtension(): bool
    {
        return $this->helper->isModuleEnable();
    }

    /**
     * Retrieve Design template Option
     *
     * @return string
     */
    public function getDesignOptions(): string
    {
        $template = '';
        try {
            $templateOptions = [
                'widget/testimonial/first-design.phtml' => '1',
                'widget/testimonial/second-design.phtml' => '2',
                'widget/testimonial/third-design.phtml' => '3',
                'widget/testimonial/fourth-design.phtml' => '4',
            ];
            $template = $templateOptions[$this->getTemplate()] ?? '0';
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $template;
    }

    /**
     * Retrieve Testimonial Heading
     *
     * @return string
     */
    public function getTestimonialHeading(): string
    {
        if (!$this->hasData('heading')) {
            $this->setData('heading', $this->helper->getTestimonialHeading());
        }

        return $this->getData('heading');
    }

    /**
     * Retrieve Testimonial Meta Heading
     *
     * @return string
     */
    public function getTestimonialMetaHeading(): string
    {
        if (!$this->hasData('sub_heading')) {
            $this->setData('sub_heading', $this->helper->getTestimonialMetaHeading());
        }

        return $this->getData('sub_heading');
    }

    /**
     * Retrieve Slider Enable
     *
     * @return bool
     */
    public function getSliderEnable(): bool
    {
        return $this->helper->getSliderEnable();
    }

    /**
     * Retrieve Arrow on slider Enabled or not
     *
     * @return bool
     */
    public function getArrow(): bool
    {
        if (!$this->hasData('arrow_slider')) {
            $this->setData('arrow_slider', $this->helper->getArrow());
        }

        return (bool)$this->getData('arrow_slider');
    }

    /**
     * Retrieve Dots on slider Enabled or not
     *
     * @return bool
     */
    public function getSliderDots(): bool
    {
        if (!$this->hasData('dots_slider')) {
            $this->setData('dots_slider', $this->helper->getSliderDotValue());
        }

        return (bool)$this->getData('dots_slider');
    }

    /**
     * Retrieve Infinite Looping on slider Enabled or not
     *
     * @return bool
     */
    public function getLooping(): bool
    {
        if (!$this->hasData('infinite_looping')) {
            $this->setData('infinite_looping', $this->helper->getEnableInfiniteLooping());
        }

        return (bool)$this->getData('infinite_looping');
    }

    /**
     * Retrieve Autoplay Enabled or not
     *
     * @return bool
     */
    public function getAutoplay(): bool
    {
        if (!$this->hasData('autoplay')) {
            $this->setData('autoplay', $this->helper->getAutoPlay());
        }

        return (bool)$this->getData('autoplay');
    }

    /**
     * Retrieve Default Slick
     *
     * @return int
     */
    public function getDefaultSlick(): int
    {
        if (!$this->hasData('default_slick')) {
            $this->setData('default_slick', $this->helper->getDefaultSlick());
        }

        return (int)$this->getData('default_slick');
    }

    /**
     * Retrieve Default Slide
     *
     * @return int
     */
    public function getDefaultSlide(): int
    {
        if (!$this->hasData('default_slide')) {
            $this->setData('default_slide', $this->helper->getDefaultSlide());
        }

        return (int)$this->getData('default_slide');
    }

    /**
     * Retrieve Default Slider Speed
     *
     * @return int
     */
    public function getSliderSpeed(): int
    {
        if (!$this->hasData('slider_speed')) {
            $this->setData('slider_speed', $this->helper->getSliderSpeed());
        }

        return (int)$this->getData('slider_speed');
    }

    /**
     * Retrieve Default Autoplay Speed
     *
     * @return int
     */
    public function getAutoplaySpeed(): int
    {
        if (!$this->hasData('autoplay_speed')) {
            $this->setData('autoplay_speed', $this->helper->getAutoPlaySpeed());
        }

        return (int)$this->getData('autoplay_speed');
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

    /**
     * Retrieve Website Field Enable on frontend form.
     *
     * @return bool
     */
    public function isWebsite(): bool
    {
        return $this->helper->isWebsite();
    }

    /**
     * Retrieve Testimonial slider Heading Enable on frontend.
     *
     * @return bool
     */
    public function enableHeading(): bool
    {
        if (!$this->hasData('enable_heading')) {
            $this->setData('enable_heading', $this->helper->isHeadingEnable());
        }

        return (bool)$this->getData('enable_heading');
    }

    /**
     * Receive configuration for Slider component
     *
     * @return string
     */
    public function getJsonConfig(): string
    {
        $breakPoints = $this->helper->getBreakpoints();
        $config = $this->serializer->serialize($this->getSliderConfig());
        return rtrim($config, '}') . ',"responsive":' . $breakPoints . '}';
    }

    /**
     * Receive Slider Store Config
     *
     * @return array
     */
    private function getSliderConfig(): array
    {
        return [
            'arrows' => $this->getArrow(),
            'infinite' => $this->getLooping(),
            'dots' => $this->getSliderDots(),
            'speed' => $this->getSliderSpeed() ? $this->getSliderSpeed() : 400,
            'slidesToShow' => $this->getDefaultSlide() ? $this->getDefaultSlide() : 4,
            'slidesToScroll' => $this->getDefaultSlick() ? $this->getDefaultSlick() : 1,
            'autoplay' => $this->getAutoPlay(),
            'autoplaySpeed' => $this->getAutoPlaySpeed() ? $this->getAutoPlaySpeed() : 2000
        ];
    }
}
