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

namespace MageINIC\Testimonial\Api\Data;

/**
 * Interface TestimonialInterface
 * @api
 */
interface TestimonialInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const TESTIMONIAL_ID = 'testimonial_id';
    public const  RATING = 'rating';
    public const  LAST_NAME = 'last_name';
    public const  CONTENT = 'content';
    public const  ENABLE = 'enable';
    public const DESIGNATION = 'designation';
    public const TITLE = 'title';
    public const FIRST_NAME = 'first_name';
    public const COMPANY = 'company';
    public const WEBSITE = 'website';
    public const EMAIL = 'email';
    public const UPLOADED_FILE = 'uploaded_file';
    public const STORE_ID = 'store_id';
    /**#@-*/

    /**
     * Retrieve title
     *
     * @return int|null
     */
    public function getTestimonialId(): ?int;

    /**
     * Set title
     *
     * @param int $id
     * @return $this
     */
    public function setTestimonialId(int $id): TestimonialInterface;

    /**
     * Retrieve title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): TestimonialInterface;

    /**
     * Retrieve content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): TestimonialInterface;

    /**
     * Retrieve rating
     *
     * @return string|null
     */
    public function getRating(): ?string;

    /**
     * Set rating
     *
     * @param string $rating
     * @return $this
     */
    public function setRating(string $rating): TestimonialInterface;

    /**
     * Retrieve first_name
     *
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): TestimonialInterface;

    /**
     * Retrieve last_name
     *
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): TestimonialInterface;

    /**
     * Retrieve email
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Set email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): TestimonialInterface;

    /**
     * Retrieve designation
     *
     * @return string|null
     */
    public function getDesignation(): ?string;

    /**
     * Set designation
     *
     * @param string $designation
     * @return $this
     */
    public function setDesignation(string $designation): TestimonialInterface;

    /**
     * Retrieve company
     *
     * @return string|null
     */
    public function getCompany(): ?string;

    /**
     * Set company
     *
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company): TestimonialInterface;

    /**
     * Retrieve company
     *
     * @return string|null
     */
    public function getWebsite(): ?string;

    /**
     * Set company
     *
     * @param string $website
     * @return $this
     */
    public function setWebsite(string $website): TestimonialInterface;

    /**
     * Retrieve enable
     *
     * @return string|null
     */
    public function getEnable(): ?string;

    /**
     * Set enable
     *
     * @param string $enable
     * @return $this
     */
    public function setEnable(string $enable): TestimonialInterface;

    /**
     * Retrieve UploadedFile
     *
     * @return string|null
     */
    public function getUploadedFile(): ?string;

    /**
     * Set UploadedFile
     *
     * @param string $uploadedFile
     * @return $this
     */
    public function setUploadedFile(string $uploadedFile): TestimonialInterface;

    /**
     * Retrieve Store ids
     *
     * @return int[]
     */
    public function getStoreId(): array;

    /**
     * Set Store ids
     *
     * @param int[] $storeId
     * @return $this
     */
    public function setStoreId(array $storeId): TestimonialInterface;
}
