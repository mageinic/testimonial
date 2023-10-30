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

use Exception;
use MageINIC\Testimonial\Api\Data\TestimonialInterface;
use MageINIC\Testimonial\Api\Data\TestimonialSearchResultsInterface;
use MageINIC\Testimonial\Api\Data\TestimonialSearchResultsInterfaceFactory as TestimonialSearchResults;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial as ResourceTestimonial;
use MageINIC\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Status;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\Validator\Url;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

/**
 * Class for TestimonialRepository
 * @api
 */
class TestimonialRepository implements TestimonialRepositoryInterface
{
    /**
     * @var EmailAddress
     */
    protected EmailAddress $emailValidator;

    /**
     * @var Url
     */
    protected Url $urlValidator;

    /**
     * @var TestimonialSearchResults
     */
    private TestimonialSearchResults $searchResultsFactory;

    /**
     * @var ResourceTestimonial
     */
    private ResourceTestimonial $resource;

    /**
     * @var TestimonialFactory
     */
    private TestimonialFactory $testimonialFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $testimonialCollectionFactory;

    /**
     * @var StoreManager
     */
    private StoreManager $storeManager;

    /**
     * @var CollectionProcessor
     */
    private CollectionProcessor $collectionProcessor;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ImageUploader
     */
    private ImageUploader $imageUploader;

    /**
     * @var DataObjectHelper
     */
    private DataObjectHelper $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private DataObjectProcessor $dataObjectProcessor;

    /**
     * TestimonialRepository Constructor.
     *
     * @param ResourceTestimonial $resource
     * @param TestimonialFactory $testimonialFactory
     * @param CollectionFactory $testimonialCollectionFactory
     * @param TestimonialSearchResults $searchResultsFactory
     * @param StoreManager $storeManager
     * @param CollectionProcessor $collectionProcessor
     * @param RequestInterface $request
     * @param EmailAddress $emailValidator
     * @param Url $urlValidator
     * @param ImageUploader $imageUploader
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        ResourceTestimonial      $resource,
        TestimonialFactory       $testimonialFactory,
        CollectionFactory        $testimonialCollectionFactory,
        TestimonialSearchResults $searchResultsFactory,
        StoreManager             $storeManager,
        CollectionProcessor      $collectionProcessor,
        RequestInterface         $request,
        EmailAddress             $emailValidator,
        Url                      $urlValidator,
        ImageUploader            $imageUploader,
        DataObjectHelper         $dataObjectHelper,
        DataObjectProcessor      $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialCollectionFactory = $testimonialCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->request = $request;
        $this->emailValidator = $emailValidator;
        $this->urlValidator = $urlValidator;
        $this->imageUploader = $imageUploader;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->testimonialCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TestimonialSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($testimonialId): bool
    {
        try {
            $this->delete($this->getById($testimonialId));
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Testimonial: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function delete(TestimonialInterface $testimonial): bool
    {
        try {
            $this->resource->delete($testimonial);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the Testimonial: %1', $exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $testimonialId): TestimonialInterface
    {
        $testimonial = $this->testimonialFactory->create();
        $this->resource->load($testimonial, $testimonialId);
        if (!$testimonial->getId()) {
            throw new NoSuchEntityException(__('Testimonial with id "%1" does not exist.', $testimonialId));
        }
        return $testimonial;
    }

    /**
     * @inheritdoc
     */
    public function createTestimonial(): TestimonialInterface
    {
        return $this->dataSetup($this->testimonialFactory->create());
    }

    /**
     * @inheritdoc
     */
    public function updateTestimonial(): TestimonialInterface
    {
        return $this->dataSetup($this->getById($this->request->getParam('testimonial_id')));
    }

    /**
     * Testimonial Data setup
     *
     * @param TestimonialInterface $model
     * @return TestimonialInterface
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws LocalizedException
     */
    protected function dataSetup(TestimonialInterface $model): TestimonialInterface
    {
        $request = $this->request->getParams();
        $request = array_filter($request, fn($value) => !($value === null) && ($value !== ''));
        $fileContent = $this->request->getFiles('uploaded_file');

        if (isset($request['email']) && !$this->emailValidator->isValid($request['email'])) {
            throw new InputException(__('Please Provide Appropriate Email Address.'));
        }

        if (isset($request['website']) && !$this->urlValidator->isValid($request['website'])) {
            throw new InputException(__('Please Provide Appropriate Website URL.'));
        }

        if (isset($fileContent['name']) && $fileContent['error'] == 0) {
            $file = $this->request->getParam('param_name', 'uploaded_file');
            $result = $this->imageUploader->saveFileToTmpDir($file);

            if (isset($result['name'])) {
                $request['uploaded_file'] = isset($result['tmp_name'])
                    ? $result['name'] : ($result['image'] ?? $result['name']);
                if (isset($result['tmp_name'])) {
                    $this->imageUploader->moveFileFromTmp($result['name']);
                }
            }
        }

        $request['enable'] = Status::PENDING;

        $requiredDataAttributes = $this->dataObjectProcessor->buildOutputDataArray(
            $model,
            TestimonialInterface::class
        );
        $testimonialData = array_merge($requiredDataAttributes, $request);
        $this->dataObjectHelper->populateWithArray(
            $model,
            $testimonialData,
            TestimonialInterface::class
        );

        try {
            $this->save($model);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __('Something thing went wrong while saving the testimonial: %1', $exception->getMessage())
            );
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function save(TestimonialInterface $testimonial): TestimonialInterface
    {
        if (empty($testimonial->getStoreId())) {
            $storeId[] = $this->storeManager->getStore()->getId();
            $testimonial->setStoreId($storeId);
        }
        try {
            $this->resource->save($testimonial);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the testimonial: %1', $exception->getMessage()));
        }
        return $testimonial;
    }
}
