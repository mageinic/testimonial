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

namespace MageINIC\Testimonial\Ui\Component\Listing\Column;

use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\Data\TestimonialInterfaceFactory as TestimonialFactory;
use MageINIC\Testimonial\Model\Testimonial\DataProcessor;
use MageINIC\Testimonial\Model\Testimonial\Image;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface as Context;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class for Thumbnail
 */
class Thumbnail extends Column
{
    /**
     * Alter Field Id
     */
    public const ALT_FIELD = TestimonialInterface::FIRST_NAME;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var Image
     */
    protected Image $testimonialImage;

    /**
     * @var DataProcessor
     */
    protected DataProcessor $dataProcessor;

    /**
     * Thumbnail Constructor.
     *
     * @param Context $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param DataProcessor $dataProcessor
     * @param Image $testimonialImage
     * @param array $components
     * @param array $data
     */
    public function __construct(
        Context            $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        DataProcessor      $dataProcessor,
        Image              $testimonialImage,
        array              $components = [],
        array              $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->dataProcessor = $dataProcessor;
        $this->testimonialImage = $testimonialImage;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['uploaded_file'])) {
                    $model = $this->dataProcessor->dataObjectProcessor($item);
                    $fieldName = $this->getData('name');
                    $url = $this->testimonialImage->getUrl($model) ?? '';
                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                        'testimonial/testimonial/edit',
                        ['testimonial_id' => $item['testimonial_id']]
                    );
                    $item[$fieldName . '_orig_src'] = $url;
                }
            }
        }

        return $dataSource;
    }

    /**
     * Retrieve Alt
     *
     * @param array $row
     * @return null|string
     */
    protected function getAlt(array $row): ?string
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return $row[$altField] ?? null;
    }
}
