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
 * @package MageINIC_Testimonial
 * @copyright Copyright (c) 2023. MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

declare(strict_types=1);

namespace MageINIC\Testimonial\Model\Testimonial;

use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\Data\TestimonialInterfaceFactory as TestimonialFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Testimonial Data Processor convert Testimonial data Array in to object
 */
class DataProcessor
{
    /**
     * @var TestimonialFactory
     */
    private TestimonialFactory $testimonialFactory;

    /**
     * @var DataObjectHelper
     */
    protected DataObjectHelper $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected DataObjectProcessor $dataObjectProcessor;

    /**
     * DataProcessor Constructor.
     *
     * @param TestimonialFactory $testimonialFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        TestimonialFactory  $testimonialFactory,
        DataObjectHelper    $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->testimonialFactory = $testimonialFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Testimonial data array convert into object
     *
     * @param array $data
     * @param TestimonialInterface|null $testimonial
     * @return TestimonialInterface
     */
    public function dataObjectProcessor(array $data, TestimonialInterface $testimonial = null): TestimonialInterface
    {
        $testimonial = $testimonial ?? $this->testimonialFactory->create();
        $requiredDataAttributes = $this->dataObjectProcessor->buildOutputDataArray(
            $testimonial,
            TestimonialInterface::class
        );
        $testimonialData = array_merge($requiredDataAttributes, $data);
        $this->dataObjectHelper->populateWithArray(
            $testimonial,
            $testimonialData,
            TestimonialInterface::class
        );

        return $testimonial;
    }
}
