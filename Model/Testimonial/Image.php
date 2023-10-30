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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

/**
 * class for Testimonial Image Url Service
 */
class Image
{
    private const ATTRIBUTE_NAME = 'uploaded_file';

    /**
     * @var FileInfo
     */
    private FileInfo $fileInfo;

    /**
     * @var StoreManager
     */
    private StoreManager $storeManager;

    /**
     * Image Construct.
     *
     * @param FileInfo $fileInfo
     * @param StoreManager $storeManager
     */
    public function __construct(
        FileInfo     $fileInfo,
        StoreManager $storeManager
    ) {
        $this->fileInfo = $fileInfo;
        $this->storeManager = $storeManager;
    }

    /**
     * Resolve Testimonial image URL
     *
     * @param TestimonialInterface $testimonial
     * @param string $attributeCode
     * @return string
     * @throws LocalizedException
     */
    public function getUrl(TestimonialInterface $testimonial, string $attributeCode = self::ATTRIBUTE_NAME): string
    {
        $url = '';
        $image = $testimonial->getData($attributeCode);

        if ($image) {
            if (is_string($image)) {
                $store = $this->storeManager->getStore();
                $mediaBaseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                if ($this->fileInfo->isBeginsWithMediaDirectoryPath($image)) {
                    $relativePath = $this->fileInfo->getRelativePathToMediaDirectory($image);
                    $url = rtrim($mediaBaseUrl, '/') . '/' . ltrim($relativePath, '/');
                } elseif (!str_starts_with($image, '/')) {
                    $url = rtrim($mediaBaseUrl, '/') . '/' . ltrim(FileInfo::ENTITY_MEDIA_PATH, '/') . '/' . $image;
                } else {
                    $url = $image;
                }
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }

        return $url;
    }
}
