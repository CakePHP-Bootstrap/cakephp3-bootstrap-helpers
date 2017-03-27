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
namespace Bootstrap\View\Helper;


/**
 * A trait that provides useful methods to manages helper that works
 * with sections that can be interwined.
 *
 * This trait work in combination with a template trait that provides
 * start and end templates for the sections.
 */
trait SectionTrait {


    /**
     * Stack of the current sections.
     *
     * @var array
     */
    protected $_sections = [];

    /**
     * Opens a new section and returns corresponding HTML.
     *
     * @param string $section Name of the part.
     * @param array $options Options for the `formatTemplate` method.
     *
     * @return string An HTML string containing formatted template.
     */
    protected function _openSection($section, $options) {
        array_push($this->_sections, $section);
        return $this->formatTemplate($section.'Start', $options);
    }

    /**
     * Close the last opened section and returns closing HTML.
     *
     * @return string An HTML string containing formatted ending template.
     */
    protected function _closeLastSection() {
        $section = array_pop($this->_sections);
        return $this->formatTemplate($section.'End', []);
    }

    /**
     * Check if a section is opened.
     *
     * @return boolean
     */
    protected function _hasOpenSection() {
        return !empty($this->_sections);
    }


    /**
     * Clear opened sections and returns an HTML string containing
     * closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _clearSections() {
        $out = '';
        while ($this->_hasOpenSection()) {
            $out .= $this->_closeLastSection();
        }
        return $out;
    }

}
