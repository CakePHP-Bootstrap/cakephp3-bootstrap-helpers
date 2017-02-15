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

use Cake\Routing\Router;


/**
 * A trait that provides a method to compare url.
 */
trait UrlComparerTrait {

    /**
     * Retrieve the relative path of the root URL from hostname.
     *
     * @return string The relative path.
     */
    protected function _relative() {
        return trim(Router::url('/'), '/');
    }

    /**
     * Retrieve the hostname (if any).
     *
     * @return string|null The hostname, or `null`.
     */
    protected function _hostname() {
        $components = parse_url(Router::url('/', true));
        if (isset($components['host'])) {
            return $components['host'];
        }
        return null;
    }

    /**
     * Checks if the given URL components match the current host.
     *
     * @param array $urlComponents URL components, typically retrieved
     * from `parse_url`.
     *
     * @return bool `true` if the components match, `false` otherwize.
     */
    protected function _matchHost($url) {
        $components = parse_url($url);
        return !(isset($components['host']) && $components['host'] != $this->_hostname());
    }

    /**
     * Remove relative part an URL (if any).
     *
     * @param string $url URL from which the relative part should be removed.
     *
     * @param string The new URL.
     */
    protected function _removeRelative($url) {
        $components = parse_url($url);
        $rela = $this->_relative();
        $path = trim($components['path'], '/');
        if ($rela && strpos($path, $rela) !== false) {
            $path = trim(substr($path, strlen($rela)), '/');
        }
        return '/'.$path;
    }

    /**
     * Normalize an URL.
     *
     * @param string $url URL to normalize.
     *
     * @return string Normalized URL.
     */
    protected function _normalize($url) {
        if (!is_string($url)) {
            $url = Router::url($url);
        }
        if (!$this->_matchHost($url)) {
            return null;
        }
        $url = Router::parse($this->_removeRelative($url));
        unset($url['?'], $url['#'], $url['plugin'], $url['pass'], $url['_matchedRoute']);
        return $this->_removeRelative(Router::url($url));
    }

    /**
     * Compare URL without regards to query parameters or hash.
     *
     * @param string|array $lhs First URL to compare.
     * @param string|array $rhs Second URL to compare. Default is current URL (`Router::url()`).
     *
     * @return bool `true` if both URL match, `false` otherwise.
     */
    public function compareUrls($lhs, $rhs = null) {
        if ($rhs == null) {
            $rhs = Router::url();
        }
        $lhs = $this->_normalize($lhs);
        $rhs = $this->_normalize($rhs);
        return $lhs !== null && $rhs !== null && $lhs == $rhs;
    }
}

?>
