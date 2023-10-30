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

namespace MageINIC\Testimonial\Model;

use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial as ResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class for Testimonial
 *
 * @api
 */
class Testimonial extends AbstractModel implements TestimonialInterface, IdentityInterface
{
    public const CACHE_TAG = "mageinic_testimonial";

    /**
     * @var string
     */
    protected $_eventPrefix = 'mageinic';

    /**
     * @var string
     */
    protected $_eventObject = 'testimonial';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::TESTIMONIAL_ID;

    /**
     * @inheritdoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getTestimonialId(): ?int
    {
        return $this->getData(self::TESTIMONIAL_ID);
    }

    /**
     * @inheritdoc
     */
    public function setTestimonialId(int $id): TestimonialInterface
    {
        return $this->setData(self::TESTIMONIAL_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): TestimonialInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $content): TestimonialInterface
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @inheritdoc
     */
    public function getRating(): ?string
    {
        return $this->getData(self::RATING);
    }

    /**
     * @inheritdoc
     */
    public function setRating(string $rating): TestimonialInterface
    {
        return $this->setData(self::RATING, $rating);
    }

    /**
     * @inheritdoc
     */
    public function getFirstName(): ?string
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setFirstName(string $firstName): TestimonialInterface
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @inheritdoc
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setLastName(string $lastName): TestimonialInterface
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @inheritdoc
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail(string $email): TestimonialInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getDesignation(): ?string
    {
        return $this->getData(self::DESIGNATION);
    }

    /**
     * @inheritdoc
     */
    public function setDesignation(string $designation): TestimonialInterface
    {
        return $this->setData(self::DESIGNATION, $designation);
    }

    /**
     * @inheritdoc
     */
    public function getCompany(): ?string
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * @inheritdoc
     */
    public function setCompany(string $company): TestimonialInterface
    {
        return $this->setData(self::COMPANY, $company);
    }

    /**
     * @inheritdoc
     */
    public function getWebsite(): ?string
    {
        return $this->getData(self::WEBSITE);
    }

    /**
     * @inheritdoc
     */
    public function setWebsite(string $website): TestimonialInterface
    {
        return $this->setData(self::WEBSITE, $website);
    }

    /**
     * @inheritdoc
     */
    public function getEnable(): ?string
    {
        return $this->getData(self::ENABLE);
    }

    /**
     * @inheritdoc
     */
    public function setEnable(string $enable): TestimonialInterface
    {
        return $this->setData(self::ENABLE, $enable);
    }

    /**
     * @inheritdoc
     */
    public function getUploadedFile(): ?string
    {
        return $this->getData(self::UPLOADED_FILE);
    }

    /**
     * @inheritdoc
     */
    public function setUploadedFile(string $uploadedFile): TestimonialInterface
    {
        return $this->setData(self::UPLOADED_FILE, $uploadedFile);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId(): array
    {
        return (array)$this->getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId(array $storeId): TestimonialInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }
}
