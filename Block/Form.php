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

use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Model\Testimonial\Image;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class for Form
 */
class Form extends Template
{
    public const SAVE_ACTION = 'testimonial/testimonial/save';

    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var Image
     */
    private Image $testimonialImage;

    /**
     * Form Constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Image $testimonialImage
     * @param array $data
     */
    public function __construct(
        Context  $context,
        Registry $registry,
        Image    $testimonialImage,
        array    $data = []
    ) {
        $this->registry = $registry;
        $this->testimonialImage = $testimonialImage;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Form Action-Url
     *
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->getUrl(self::SAVE_ACTION);
    }

    /**
     * Retrieve Registry
     *
     * @return Registry
     */
    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    /**
     * Retrieve Image FullPath
     *
     * @param TestimonialInterface $testimonial
     * @return string
     * @throws LocalizedException
     */
    public function getImageFullPath(TestimonialInterface $testimonial): string
    {
        return $this->testimonialImage->getUrl($testimonial);
    }
}
