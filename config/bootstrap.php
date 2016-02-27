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

use Cake\Core\Configure;
use Cake\Database\Type;
use Cake\Event\EventManager;

try {
	Configure::load('config', 'default', true);
} catch (\Exception $e) {
	//die('Unable to load Config/settings file.');
}

Type::map('json', 'Platform\Database\Type\JsonType');

EventManager::instance()->attach(
	new Platform\Event\CoreEvent,
    null,
	['priority' => 1]
);
