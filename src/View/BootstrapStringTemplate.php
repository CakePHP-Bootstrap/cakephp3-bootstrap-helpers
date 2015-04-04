<?php 
namespace Bootstrap3\View;

use Cake\View\StringTemplate;
use Cake\Utility\Hash;

class BootstrapStringTemplate extends StringTemplate {

    /**
     * Compile templates into a more efficient printf() compatible format.
     *
     * @param array $templates The template names to compile. If empty all templates will be compiled.
     * @return void
     */
    protected function _compileTemplates(array $templates = [])
    {
        if (empty($templates)) {
            $templates = array_keys($this->_config);
        }
        foreach ($templates as $name) {
            $template = $this->get($name);
            if ($template === null) {
                $this->_compiled[$name] = [null, null];
            }

            preg_match_all('#\{\{([\w.]+)\}\}#', $template, $matches);
            $this->_compiled[$name] = [
                str_replace($matches[0], '%s', $template),
                $matches[1]
            ];
        }
    }

    /**
     * Format a template string with $data
     *
     * @param string $name The template name.
     * @param array $data The data to insert.
     * @return string
    */
    public function format($name, array $data)
    {
        if (!isset($this->_compiled[$name])) {
            return '';
        }
        list($template, $placeholders) = $this->_compiled[$name];
        /* If there is a {{attrs.class}} block in $template, remove classes from $data['attrs']
           and put them in $data['attrs.class']. */
        if (isset($data['attrs'])) {
            foreach ($placeholders as $placeholder) {
                if (substr($placeholder, 0, 6) == 'attrs.'
                    && in_array('attrs.'.substr($placeholder, 6), $placeholders)
                    && preg_match('#'.substr($placeholder, 6).'="([^"]+)"#', $data['attrs'], $matches) > 0) {
                    preg_replace('#'.substr($placeholder, 6).'="[^"]+"#', '', $data['attrs']);
                    $data[$placeholder] = $matches[1];
                }  
            }
        }
        if ($template === null) {
            return '';
        }
        $replace = [];
        foreach ($placeholders as $placeholder) {
            $replace[] = isset($data[$placeholder]) ? $data[$placeholder] : null;
        }
        return vsprintf($template, $replace);
    }

};