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

namespace MageINIC\Testimonial\Model\ResourceModel\Testimonial;

use Exception;
use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Model\ResourceModel\AbstractCollection;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial as ResourceModel;
use MageINIC\Testimonial\Model\Testimonial as Model;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;

/**
 * Class for Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var bool|null
     */
    protected ?bool $_previewFlag = null;

    /**
     * @var string
     */
    protected $_idFieldName = TestimonialInterface::TESTIMONIAL_ID;

    /**
     * Add Store Filter.
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, bool $withAdmin = true): Collection
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Set First Store Flag
     *
     * @param bool $flag
     * @return $this
     */
    public function setFirstStoreFlag(bool $flag = false): Collection
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);

        $this->_map['fields']['testimonial_id'] = 'main_table.testimonial_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Load after
     *
     * @return AbstractCollection
     * @throws NoSuchEntityException|Exception
     */
    protected function _afterLoad(): AbstractCollection
    {
        $entityMetadata = $this->metadataPool->getMetadata(TestimonialInterface::class);
        $this->performAfterLoad('mageinic_testimonial_store', $entityMetadata->getLinkField());
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    /**
     * Render Filters Before
     *
     * @return void
     * @throws Exception
     */
    protected function _renderFiltersBefore(): void
    {
        $entityMetadata = $this->metadataPool->getMetadata(TestimonialInterface::class);
        $this->joinStoreRelationTable('mageinic_testimonial_store', $entityMetadata->getLinkField());
    }
}
