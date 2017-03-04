<?php
/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 * You may obtain a copy of the License at
 *
 *     https://opensource.org/licenses/mit-license.php
 *
 *
 * @copyright Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Bootstrap\View\Widget;

use Cake\View\Widget\LabelWidget;

/**
 * Form 'widget' for creating labels that contain inline radio buttons.
 *
 * Generally this element is used by other widgets,
 * and FormHelper itself.
 */
class InlineRadioNestingLabelWidget extends LabelWidget {

    /**
     * The template to use.
     *
     * @var string
     */
    protected $_labelTemplate = 'inlineRadioNestingLabel';

};
