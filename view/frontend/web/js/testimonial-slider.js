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

define([
    'jquery',
    'jquery-ui-modules/widget',
    'slick',
    'read-more'
], function ($) {
    'use strict';

    $.widget('mageinic.TestimonialSlider', {
        selectors: {
            slickInit: '.slick-initialized'
        },

        /**
         * @inheritDoc
         */
        _create: function () {
            var self = this;
            $(self.element).not(self.selectors.slickInit).slick({
                arrows: self.options.arrows,
                autoplay: self.options.autoplay,
                autoplaySpeed: self.options.autoplaySpeed ? self.options.autoplaySpeed : 300,
                dots: self.options.dots,
                infinite: self.options.infinite,
                slidesToShow: self.options.slidesToShow ? self.options.slidesToShow : 3,
                slidesToScroll: self.options.slidesToScroll ? self.options.slidesToScroll : 2,
                speed: self.options.speed ? self.options.speed : 400,
                responsive: self.options.responsive
            });

            $('.testimonial-main .slick-slide').each(function () {
                $(this).find('.content-testimonial').readmore({
                    speed: 500,
                    collapsedHeight: 85,
                    lessLink: "<span class='toggle-less-link'></span>",
                    moreLink: "<span class='toggle-more-link'></span>"
                });
            });
        }
    });

    return $.mageinic.TestimonialSlider;
});
