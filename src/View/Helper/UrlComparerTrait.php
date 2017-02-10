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
     * Normalize an URL.
     *
     * @param string $url URL to normalize.
     * @param bool $query Remove query parameters. Default is `true`.
     * @param bool $hash Remove hash. Default is `true`.
     *
     * @return string Normalized URL.
     */
    protected function _normalize($url, $query = true, $hash = true) {
        $url = Router::normalize($url);
        if ($hash) {
            list($url, ) = explode('#', $url);
        }
        if ($query) {
            $url = Router::parse($url);
            unset($url['?'], $url['#'], $url['plugin'], $url['pass'], $url['_matchedRoute']);
            $url = Router::normalize(Router::url($url));
        }
        return $url;
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
        $lhs = Router::url($lhs, true);
        $rhs = Router::url($rhs, true);
        return $this->_normalize($lhs) == $this->_normalize($rhs);
    }
}

?>