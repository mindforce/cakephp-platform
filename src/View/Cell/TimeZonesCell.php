<?php
/**
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Mindforce Team (http://mindforce.me)
* @link          http://mindforce.me Platform CakePHP 3 Plugin
* @since         0.0.1
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace Platform\View\Cell;

use Cake\View\Cell;
use Cake\Core\App;
use Cake\Error;
use \DateTimeZone;
/**
 * CacheEngine cell
 */
class TimeZonesCell extends Cell {


/**
 * Default display method.
 *
 * @return void
 */
    public function display($path, $options = []) {
        $zones = DateTimeZone::listIdentifiers();
        $zones = array_combine(array_values($zones), array_values($zones));
        unset($zones['UTC']);
        $options['options'] = ['UTC' => 'UTC'] + $zones;
        $this->set(compact('path', 'options'));
    }
}
