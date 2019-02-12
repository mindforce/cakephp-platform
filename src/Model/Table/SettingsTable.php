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

namespace Platform\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\Core\Configure\Engine\PhpConfig;

/**
 * Settings Model
 */
class SettingsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
    public function initialize(array $config) {
        $this->setTable('platform_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
    public function validationDefault(Validator $validator) {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('plugin', 'create')
            ->notEmpty('plugin')
            ->requirePresence('path', 'create')
            ->notEmpty('path')
            ->allowEmpty('value');

        return $validator;
    }

    public function findExtended(Query $query, array $options = []) {
        $mapper = function($setting, $path, $mapReduce) {
            $prototype = $this->getSettingPrototype($setting->path, $setting->plugin);
            foreach ($prototype as $field=>$value){
                $setting->{$field} = $value;
            }
            $mapReduce->emit($setting, $path);
        };
        return $query->mapReduce($mapper);
    }

    public function findEditable(Query $query, array $options = []) {
        $mapper = function($setting, $path, $mapReduce) {
            $type = 'editable';
            if($setting->hidden == true) $type = 'hidden';
            $mapReduce->emitIntermediate($setting, $type);
        };
        $reducer = function($articles, $type, $mapReduce) {
            $mapReduce->emit($articles, $type);
        };
        return $query->mapReduce($mapper, $reducer);
    }

    protected function getSettingPrototype($path, $plugin = null){
        $defaults = [
            'title' => null,
            'description' => null,
            'cell' => null,
            'default' => null,
            'options' => null,
            'hidden' => false,
        ];
        //TODO: maybe implement cache for reader results or remember results in table class in future
        $settingsReader = new PhpConfig();
        $input = 'settings';
        if(!empty($plugin)){
            $input = $plugin.'.settings';
        }
        $settingPrototypes = $settingsReader->read($input);
        $setting = Hash::extract($settingPrototypes, '{n}[path='.$path.']');
        if($setting = Hash::get($setting, '0', null)){
            return array_merge($defaults, $setting);
        }
        return [];
    }


}
