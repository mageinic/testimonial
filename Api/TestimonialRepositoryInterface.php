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

namespace MageINIC\Testimonial\Api;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface TestimonialRepositoryInterface
 * @api
 */
interface TestimonialRepositoryInterface
{
    /**
     * Create Testimonial
     *
     * @return \MageINIC\Testimonial\Api\Data\TestimonialInterface
     * @throws InputException
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function createTestimonial(): TestimonialInterface;

    /**
     * Update Testimonial
     *
     * @return \MageINIC\Testimonial\Api\Data\TestimonialInterface
     * @throws InputException
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function updateTestimonial(): TestimonialInterface;

    /**
     * Save Testimonial
     *
     * @param \MageINIC\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return \MageINIC\Testimonial\Api\Data\TestimonialInterface
     * @throws CouldNotSaveException|NoSuchEntityException
     */
    public function save(TestimonialInterface $testimonial): TestimonialInterface;

    /**
     * Retrieve Testimonial
     *
     * @param  int $testimonialId
     * @return \MageINIC\Testimonial\Api\Data\TestimonialInterface
     * @throws LocalizedException|Exception
     */
    public function getById(int $testimonialId): TestimonialInterface;

    /**
     * Retrieve Testimonial matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageINIC\Testimonial\Api\Data\TestimonialSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Testimonial
     *
     * @param  \MageINIC\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(TestimonialInterface $testimonial): bool;

    /**
     * Delete Testimonial by ID
     *
     * @param  int $testimonialId
     * @return bool true on success
     * @throws CouldNotDeleteException|LocalizedException
     */
    public function deleteById(int $testimonialId): bool;
}
