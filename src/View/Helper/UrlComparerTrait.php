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
    protected function _matchHost($urlComponents) {
        if (isset($urlComponents['host']) && $urlComponents['host'] != $this->_hostname()) {
            return null;
        }
        $rela = $this->_relative();
        $path = trim($urlComponents['path'], '/');
        if ($rela && strpos($path, $rela) === false) {
            return null;
        }
        return '/'.trim(substr($path, strlen($rela)), '/');
    }

    /**
     * Normalize an URL.
     *
     * @param string $url URL to normalize.
     *
     * @return string Normalized URL.
     */
    protected function _normalize($url) {
        $url = Router::normalize(Router::url($url, true));
        $url = $this->_matchHost(parse_url($url));
        if (!$url) {
            return null;
        }
        $url = Router::parse($url);
        unset($url['?'], $url['#'], $url['plugin'], $url['pass'], $url['_matchedRoute']);
        return Router::url($url);
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
