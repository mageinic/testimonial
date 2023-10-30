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
 * @package MageINIC_<ModuleName>
 * @copyright Copyright (c) 2023. MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\Testimonial\Block;

use Exception;
use MageINIC\Testimonial\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface as Manager;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class for Testimonial slider on home page
 */
class TestimonialHome extends Template
{
    public const PATH_TO_ENABLE_ON_HOME = 'testimonial/general/enable_in_homepage';

    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'MageINIC_Testimonial::testimonial-home.phtml';

    /**
     * @var Manager
     */
    protected Manager $messageManager;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var Data
     */
    private Data $helper;

    /**
     * TestimonialHome Constructor.
     *
     * @param Context $context
     * @param Data $helper
     * @param Manager $messageManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data    $helper,
        Manager $messageManager,
        array   $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        parent::__construct($context, $data);
    }

    /**
     * Add Testimonial Slider Template
     *
     * @return string
     */
    public function addTemplate(): string
    {
        $template = '';
        try {
            $designOptions = [1 => 'first', 2 => 'second', 3 => 'third', 4 => 'fourth'];
            $design = $designOptions[$this->helper->getDesignOptions()] ?? 'default';
            $template = "widget/testimonial/$design-design.phtml";
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $template;
    }

    /**
     * Enable Testimonial Slider on Home Page
     *
     * @return bool
     */
    public function isEnableHome(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::PATH_TO_ENABLE_ON_HOME, ScopeInterface::SCOPE_STORE);
    }
}
