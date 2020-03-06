<?php
declare(strict_types=1);

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @link        https://holt59.github.io/cakephp3-bootstrap-helpers/
 */
namespace Bootstrap\View\Widget;

use Cake\View\Widget\LabelWidget;

/**
 * Label widget class for generating label or legend depending on type of
 * inputs.
 *
 * This class is intended as an internal implementation detail
 * of Cake\View\Helper\FormHelper and is not intended for direct use.
 */
class LabelLegendWidget extends LabelWidget
{
     /**
      * The template to use for labels.
      *
      * @var string
      */
     protected $_templateForLabel = 'label';

     /**
      * The template to use for legends.
      *
      * @var string
      */
     protected $_templateForLegend = 'labelLegend';

    /**
     * {@inheritDoc}
     */
    public function render(array $data, \Cake\View\Form\ContextInterface $context): string
    {
        if (isset($data['for']) && $data['for'] === false) {
            $this->_labelTemplate = $this->_templateForLegend;
        } else {
            $this->_labelTemplate = $this->_templateForLabel;
        }

        return parent::render($data, $context);
    }
}
