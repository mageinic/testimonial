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

namespace MageINIC\Testimonial\Controller\Adminhtml\Testimonial;

use Exception;
use MageINIC\Testimonial\Controller\Adminhtml\Testimonial;
use MageINIC\Testimonial\Ui\Component\Listing\Column\Options\Status;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class for Save
 */
class Save extends Testimonial implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MageINIC_Testimonial::save';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('testimonial_id');
            if (empty($data['testimonial_id'])) {
                $data['testimonial_id'] = null;
            }

            try {
                $model = $id ? $this->testimonialRepository->getById($id): $this->testimonialFactory->create();
                ;
            } catch (LocalizedException|Exception $e) {
                $this->messageManager->addErrorMessage(__('This testimonial no longer exists.'), $e);
                return $resultRedirect->setPath('*/*/');
            }

            if (isset($data['uploaded_file'][0]['name']) && isset($data['uploaded_file'][0]['tmp_name'])) {
                $data['uploaded_file'] = $data['uploaded_file'][0]['name'];
                try {
                    $this->imageUploader->moveFileFromTmp($data['uploaded_file']);
                } catch (FileSystemException|LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            } else {
                $data['uploaded_file'] = $data['uploaded_file'][0]['image'] ??
                    ($data['uploaded_file']['delete'] ? null : $data['uploaded_file']['value'] ?? null);
            }
            $data['enable'] = ($data['enable'] == Status::PENDING) ? Status::DISABLED : $data['enable'];

            $model->setData($data);
            try {
                $this->testimonialRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the testimonial.'));
                $this->dataPersistor->clear('mageinic_testimonial');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the testimonial slider.')
                );
            }
            $this->dataPersistor->set('mageinic_testimonial', $data);
            return $resultRedirect->setPath('*/*/edit', [
                'testimonial_id' => $this->getRequest()->getParam('testimonial_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
