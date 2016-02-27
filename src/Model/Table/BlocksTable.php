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

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Blocks Model
 */
class BlocksTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('platform_blocks');
		$this->displayField('title');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');

		$this->hasMany('Cells', [
			'foreignKey' => 'block_id',
			'className' => 'Platform.Cells',
		]);
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
			->requirePresence('title', 'create')
			->notEmpty('title')
			->requirePresence('slug', 'create')
			->notEmpty('slug')
			->add('admin', 'valid', ['rule' => 'boolean'])
			->allowEmpty('admin')
			->add('cell_count', 'valid', ['rule' => 'numeric'])
			->requirePresence('cell_count', 'create')
			->notEmpty('cell_count');

		return $validator;
	}

}
