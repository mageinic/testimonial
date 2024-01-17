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

namespace MageINIC\Testimonial\Helper;

use Magento\Contact\Model\ConfigInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

/**
 * Class for Data
 */
class Data extends AbstractHelper
{
    /**#@+
     * Constants for Store Config Value Path.
     */
    public const PATH_TO_TESTIMONIAL_ENABLE_FRONTEND = 'testimonial/general/enable_frontend';
    public const XML_PATH_ENABLED = ConfigInterface::XML_PATH_ENABLED;
    public const PATH_TO_TESTIMONIAL_HEADING = 'testimonial/general/heading';
    public const PATH_TO_TESTIMONIAL_META_HEADING = 'testimonial/general/meta_heading';
    public const XML_PATH_WEBSITE_ENABLED = 'testimonial/general/enable_website';
    public const XML_PATH_RECAPTCHA_ENABLED = 'testimonial/general/enable_recaptcha';
    public const PATH_TO_TESTIMONIAL_SLIDER_ENABLE = 'testimonial/general/enable_slider';
    public const ENABLE_FRONTEND = 'testimonial/general/enable_frontend';
    public const ENABLE_HEADING = 'testimonial/general/enable_heading';
    public const ARROW_SLIDER = 'testimonial/slider/arrow_slider';
    public const DOTS_SLIDER = 'testimonial/slider/dots_slider';
    public const INFINITE_LOOPING = 'testimonial/slider/infinite_looping';
    public const SLIDER_SPEED = 'testimonial/slider/slider_speed';
    public const SLIDER_TO_DESKTOP = 'testimonial/slider/default_slide';
    public const DEFAULT_SLICK = 'testimonial/slider/default_slick';
    public const DESIGN_OPTIONS = 'testimonial/slider/design_options';
    public const AUTOPLAY = 'testimonial/slider/autoplay';
    public const AUTOPLAY_SPEED = 'testimonial/slider/autoplay_speed';
    public const BREAKPOINT = 'testimonial/slider/breakpoint_field';
    public const XML_PATH_FORM_ENABLED = 'testimonial/general/enable_form';
    /**#@-*/

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var View
     */
    private View $customerViewHelper;

    /**
     * @var StoreManager
     */
    private StoreManager $storeManager;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * @var DataPersistorInterface|null
     */
    private ?DataPersistorInterface $dataPersistor = null;

    /**
     * @var array|null
     */
    private ?array $postData = null;

    /**
     * Data Constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param View $customerViewHelper
     * @param StoreManager $storeManager
     * @param Serializer $serializer
     */
    public function __construct(
        Context      $context,
        Session      $customerSession,
        View         $customerViewHelper,
        StoreManager $storeManager,
        Serializer   $serializer
    ) {
        $this->customerSession = $customerSession;
        $this->customerViewHelper = $customerViewHelper;
        $this->storeManager = $storeManager;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Receive username
     *
     * @return string
     */
    public function getUserName(): string
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '';
        }
        /** @var CustomerInterface $customer */
        $customer = $this->customerSession->getCustomerDataObject();

        return trim($this->customerViewHelper->getCustomerName($customer));
    }

    /**
     * Receive user email
     *
     * @return string
     */
    public function getUserEmail(): string
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '';
        }
        /** @var CustomerInterface $customer */
        $customer = $this->customerSession->getCustomerDataObject();

        return $customer->getEmail();
    }

    /**
     * Receive user First Name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '';
        }
        /** @var CustomerInterface $customer */
        $customer = $this->customerSession->getCustomerDataObject();

        return $customer->getFirstname();
    }

    /**
     * Receive user Last Name
     *
     * @return string
     */
    public function getLastName(): string
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '';
        }
        /** @var CustomerInterface $customer */
        $customer = $this->customerSession->getCustomerDataObject();

        return $customer->getLastname();
    }

    /**
     * Receive value from POST by key
     *
     * @param string $key
     * @return string
     */
    public function getPostValue(string $key): string
    {
        if (null === $this->postData) {
            $this->postData = (array)$this->getDataPersistor()->get('contact_us');
            $this->getDataPersistor()->clear('contact_us');
        }

        if (isset($this->postData[$key])) {
            return (string)$this->postData[$key];
        }

        return '';
    }

    /**
     * Receive Data Persistor
     *
     * @return DataPersistorInterface
     */
    public function getDataPersistor(): DataPersistorInterface
    {
        if ($this->dataPersistor === null) {
            $this->dataPersistor = ObjectManager::getInstance()
                ->get(DataPersistorInterface::class);
        }
        return $this->dataPersistor;
    }

    /**
     * Receive Current Store Id.
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function getCurrentStoreId(): int
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Receive Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'gp';
    }

    /**
     * Receive Enable Config
     *
     * @return bool
     */
    public function getEnableExtension(): bool
    {
        return $this->scopeConfig->getValue(
            self::PATH_TO_TESTIMONIAL_ENABLE_FRONTEND,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Design Options config
     *
     * @return string|null
     */
    public function getDesignOptions(): ?string
    {
        return $this->scopeConfig->getValue(
            self::DESIGN_OPTIONS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Module Enabled.
     *
     * @return bool
     */
    public function isModuleEnable(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::ENABLE_FRONTEND,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Testimonial slider Heading Enable on frontend.
     *
     * @return bool
     */
    public function isHeadingEnable(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::ENABLE_HEADING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Receive Website Field Enable on frontend form.
     *
     * @return bool
     */
    public function isWebsite(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_WEBSITE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Receive Recaptcha Enable on frontend form.
     *
     * @return bool
     */
    public function isRecaptcha(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_RECAPTCHA_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Receive Testimonial form,grid,tab Enable on frontend.
     *
     * @return bool
     */
    public function isForm(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_FORM_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive SliderDot Value config
     *
     * @return bool
     */
    public function getSliderDotValue(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::DOTS_SLIDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Infinite Looping config
     *
     * @return bool
     */
    public function getEnableInfiniteLooping(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::INFINITE_LOOPING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Slider Enable
     *
     * @return bool
     */
    public function getSliderEnable(): bool
    {
        return $this->scopeConfig->getValue(
            self::PATH_TO_TESTIMONIAL_SLIDER_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Slider Speed config
     *
     * @return int
     */
    public function getSliderSpeed(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::SLIDER_SPEED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Slide To Desktop config
     *
     * @return int
     */
    public function getDefaultSlide(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::SLIDER_TO_DESKTOP,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Slick To Desktop config
     *
     * @return int
     */
    public function getDefaultSlick(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::DEFAULT_SLICK,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive AutoPlay Data
     *
     * @return bool
     */
    public function getAutoPlay(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::AUTOPLAY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive AutoPlay Speed
     *
     * @return int
     */
    public function getAutoPlaySpeed(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::AUTOPLAY_SPEED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive AutoPlay Speed
     *
     * @return bool
     */
    public function getArrow(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::ARROW_SLIDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Testimonial Heading
     *
     * @return string
     */
    public function getTestimonialHeading(): string
    {
        return $this->scopeConfig->getValue(
            self::PATH_TO_TESTIMONIAL_HEADING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Testimonial Sub Heading
     *
     * @return string
     */
    public function getTestimonialMetaHeading(): string
    {
        return $this->scopeConfig->getValue(
            self::PATH_TO_TESTIMONIAL_META_HEADING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receive Breakpoint config value
     *
     * @return string
     */
    public function getBreakpointConfig(): string
    {
        return $this->scopeConfig->getValue(self::BREAKPOINT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Receive Slider Breakpoints.
     *
     * @return string
     */
    public function getBreakpoints(): string
    {
        $breakpoints = $this->getBreakpointConfig();
        $breakpoints = $this->serializer->unserialize($breakpoints);

        $values = [];
        foreach ($breakpoints as $breakpoint) {
            $values[] = [
                "breakpoint" => (int)$breakpoint['breakpoint'],
                "settings" => [
                    "slidesToShow" => (int)$breakpoint['slides_to_show'],
                    "slidesToScroll" => (int)$breakpoint['slides_to_scroll'],
                    "dots" => (bool)$breakpoint['dots'],
                    "infinite" => (bool)$breakpoint['infinite'],
                ],
            ];
        }

        return $this->serializer->serialize($values);
    }
}
