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

namespace MageINIC\Testimonial\Observer;

use MageINIC\Testimonial\Helper\Data as HelperData;
use Magento\Captcha\Helper\Data;
use Magento\Captcha\Observer\CaptchaStringResolver;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class for CheckTestimonialFormObserver
 */
class CheckTestimonialFormObserver implements ObserverInterface
{
    /**
     * @var ActionFlag
     */
    private ActionFlag $actionFlag;

    /**
     * @var Data
     */
    private Data $captchaHelper;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var RedirectInterface
     */
    private RedirectInterface $redirect;

    /**
     * @var CaptchaStringResolver
     */
    private CaptchaStringResolver $captchaStringResolver;

    /**
     * @var DataPersistorInterface|null
     */
    private ?DataPersistorInterface $dataPersistor = null;

    /**
     * @var HelperData
     */
    private HelperData $testimonialHelper;

    /**
     * CheckTestimonialFormObserver Constructor.
     *
     * @param Data $captchaHelper
     * @param ActionFlag $actionFlag
     * @param ManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param CaptchaStringResolver $captchaStringResolver
     * @param HelperData $testimonialHelper
     */
    public function __construct(
        Data                  $captchaHelper,
        ActionFlag            $actionFlag,
        ManagerInterface      $messageManager,
        RedirectInterface     $redirect,
        CaptchaStringResolver $captchaStringResolver,
        HelperData            $testimonialHelper
    ) {
        $this->captchaHelper = $captchaHelper;
        $this->actionFlag = $actionFlag;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->captchaStringResolver = $captchaStringResolver;
        $this->testimonialHelper = $testimonialHelper;
    }

    /**
     * Execute.
     *
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        if ($this->testimonialHelper->isRecaptcha()) {
            $formId = 'testimonial_form';
            $captcha = $this->captchaHelper->getCaptcha($formId);
            if ($captcha->isRequired()) {
                $controller = $observer->getControllerAction();
                if (!$captcha->isCorrect($this->captchaStringResolver->resolve($controller->getRequest(), $formId))) {
                    $this->messageManager->addErrorMessage(__('Incorrect CAPTCHA.'));
                    $this->getDataPersistor()->set($formId, $controller->getRequest()->getPostValue());
                    $this->actionFlag->set('', ActionInterface::FLAG_NO_DISPATCH, true);
                    $this->redirect->redirect(
                        $controller->getResponse(),
                        $controller->getRequest()->getServer('HTTP_REFERER')
                    );
                }
            }
        }
    }

    /**
     * Retrieve Data Persistor
     *
     * @return DataPersistorInterface|null
     */
    private function getDataPersistor(): ?DataPersistorInterface
    {
        if ($this->dataPersistor === null) {
            $this->dataPersistor = ObjectManager::getInstance()->get(DataPersistorInterface::class);
        }
        return $this->dataPersistor;
    }
}
