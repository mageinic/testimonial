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

namespace MageINIC\Testimonial\Controller\Testimonial;

use Exception;
use MageINIC\Testimonial\Api\Data\TestimonialInterfaceFactory as TestimonialFactory;
use MageINIC\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use MageINIC\Testimonial\Helper\Data;
use MageINIC\Testimonial\Model\Testimonial\FileInfo;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Status;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\Validator\Url;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class for Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var EmailAddress
     */
    protected EmailAddress $emailAddress;

    /**
     * @var TestimonialRepository
     */
    private TestimonialRepository $testimonialRepository;

    /**
     * @var  TestimonialFactory
     */
    private TestimonialFactory $testimonialFactory;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Data
     */
    private Data $helper;

    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;

    /**
     * @var Url
     */
    private Url $urlValidation;

    /**
     * Save Constructor.
     *
     * @param Context $context
     * @param TestimonialRepository $testimonialRepository
     * @param TestimonialFactory $testimonialFactory
     * @param Request $request
     * @param Data $helper
     * @param Filesystem $fileSystem
     * @param UploaderFactory $uploaderFactory
     * @param EmailAddress $emailAddress
     * @param Url $urlValidation
     */
    public function __construct(
        Context               $context,
        TestimonialRepository $testimonialRepository,
        TestimonialFactory    $testimonialFactory,
        Request               $request,
        Data                  $helper,
        Filesystem            $fileSystem,
        UploaderFactory       $uploaderFactory,
        EmailAddress          $emailAddress,
        Url                   $urlValidation
    ) {
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialRepository = $testimonialRepository;
        $this->helper = $helper;
        $this->filesystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->request = $request;
        $this->emailAddress = $emailAddress;
        $this->urlValidation = $urlValidation;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResponseInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $id = $this->getRequest()->getParam('testimonial_id');
        //$data = $this->getRequest()->getFiles()->toArray();
//        echo "<pre>";
//        print_r($data);die();

        if (empty($data['testimonial_id'])) {
            $data['testimonial_id'] = null;
        }

        try {
            $model = $id ? $this->testimonialRepository->getById($id) : $this->testimonialFactory->create();
        } catch (LocalizedException|Exception $e) {
            $this->messageManager->addErrorMessage(__('This testimonial no longer exists.'), $e);
            return $this->_redirect('*/*/');
        }

        $files = $this->request->getFiles()->toArray();
        $imageName = $files['uploaded_file']['name'];

        if ($imageName === null) {
            try {
                $this->uploadImage();
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->_redirect($this->_redirect->getRefererUrl());
            }
        }

        if ($this->helper->getFirstName()) {
            if (isset($data['email']) && !$this->emailAddress->isValid($data['email'])) {
                throw $this->messageManager->addErrorMessage(__('provide proper Email Address.'));
            }
            if (isset($data['website']) && !$this->urlValidation->isValid($data['website'])) {
                $this->messageManager->addErrorMessage(__('provide proper website URL.'));
            }
            $data['rating'] = $data['rating'] ?? 0;
            $data['first_name'] = $this->helper->getFirstName();
            $data['last_name'] = $this->helper->getLastName();
            $data['email'] = $this->helper->getUserEmail();

            $imageName = $imageName ? str_replace(" ", "_", $imageName) : ($data['old_image'] ?? '');
            $data['uploaded_file'] = $imageName;
            $data['store_id'] = [$this->helper->getCurrentStoreId()];
            $data['enable'] = Status::PENDING;
        }

        try {
            $model->setData($data);
            $this->testimonialRepository->save($model);
            $this->messageManager->addSuccessMessage(__('Your Testimonial Saved.'));
        } catch (Exception $e) {
            throw $this->messageManager->addErrorMessage(__('Something went wrong while saving the Testimonial.'), $e);
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * Upload Image
     *
     * @return void
     * @throws Exception
     */
    private function uploadImage(): void
    {
        $uploader = $this->uploaderFactory->create(['fileId' => 'uploaded_file']);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(false);
        $uploader->setAllowRenameFiles(true);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath(FileInfo::ENTITY_MEDIA_PATH . '/');
        $uploader->save($path);
    }
}
