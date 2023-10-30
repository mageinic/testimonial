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

namespace MageINIC\Testimonial\Model\Testimonial;

use MageINIC\Testimonial\Model\ResourceModel\Testimonial\Collection;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use MageINIC\Testimonial\Model\Testimonial;
use Magento\Framework\App\Request\DataPersistorInterface as DataPersistor;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Io\File;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class for DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistor
     */
    private DataPersistor $dataPersistor;

    /**
     * @var array|null
     */
    private ?array $loadedData = null;

    /**
     * @var Image
     */
    protected Image $testimonialImage;

    /**
     * @var FileInfo
     */
    protected FileInfo $fileInfo;

    /**
     * @var File
     */
    protected File $fileSystemIo;

    /**
     * DataProvider Constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistor $dataPersistor
     * @param Image $testimonialImage
     * @param FileInfo $fileInfo
     * @param File $fileSystemIo
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistor $dataPersistor,
        Image $testimonialImage,
        FileInfo $fileInfo,
        File $fileSystemIo,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->testimonialImage = $testimonialImage;
        $this->fileInfo = $fileInfo;
        $this->fileSystemIo = $fileSystemIo;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Retrieve Data
     *
     * @return array
     * @throws NoSuchEntityException|FileSystemException|LocalizedException
     */
    public function getData(): array
    {
        if (!isset($this->loadedData)) {
            $items = $this->collection->getItems();

            /** @var Testimonial $model */
            foreach ($items as $model) {
                $fileName = $model->getData('uploaded_file');
                if ($this->fileInfo->isExist($fileName)) {
                    $fileIo = $this->fileSystemIo->getPathInfo($fileName);
                    $stat = $this->fileInfo->getStat($fileName);
                    $mime = $this->fileInfo->getMimeType($fileName);
                    $img = [
                        'image' => $fileName,
                        'name'  => $fileIo['basename'],
                        'url'   => $this->testimonialImage->getUrl($model),
                        'size'  => $stat['size'],
                        'type'  => $mime,
                    ];
                    $model->setData('uploaded_file', [$img]);
                }

                $this->loadedData[$model->getId()] = $model->getData();
            }

            $data = $this->dataPersistor->get('mageinic_testimonial');
            if (!empty($data)) {
                $model = $this->collection->getNewEmptyItem();
                $model->setData($data);
                $this->loadedData[$model->getId()] = $model->getData();
                $this->dataPersistor->clear('mageinic_testimonial');
            }
            $this->loadedData ??= [];
        }

        return $this->loadedData;
    }
}
