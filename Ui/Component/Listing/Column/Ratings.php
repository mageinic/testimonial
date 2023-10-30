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

namespace MageINIC\Testimonial\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class for Ratings.
 */
class Ratings extends Column
{
    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as $key => $items) {
                $rating = $items['rating'];

                $html = '<div class="rating-field-label" ';
                $html .= 'style = "direction: rtl; display: inline-block; margin: 3px 0 0; ';
                $html .= 'unicode-bidi: bidi-override; vertical-align: top; font-size: 1.3em;">';
                for ($i = 5; $i >= 1; $i--) {
                    $starColor = ($i <= $rating) ? '#f30' : 'rgb(204, 204, 204)';
                    $html .= '<label for="star-' . $i . '" title="Ratings">';
                    $html .= '<span class="' . $i . '" style="color: ' . $starColor . '">&#9733;</span>';
                    $html .= '</label>';
                }
                $html .= '</div>';
                $dataSource['data']['items'][$key]['rating'] = $html;
            }
        }

        return $dataSource;
    }
}
