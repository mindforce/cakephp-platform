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

use Phinx\Migration\AbstractMigration;

class PlatformInitialMigration extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
	    $blocks = $this->table('platform_blocks');
	    $blocks->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('slug', 'string', ['limit' => 255])
            ->addColumn('admin', 'boolean', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
		    ->addColumn('cell_count', 'integer', ['null' => true, 'default' => 0])
		    ->addIndex(['slug'], array('unique' => true, 'name' => 'platform_blocks_slug_idx'))
		    ->addIndex(['slug', 'admin'], array('unique' => true, 'name' => 'platform_blocks_slug_admin_idx'))
            ->save();

	    $cells = $this->table('platform_cells');
	    $cells->addColumn('block_id', 'integer', ['default' => 0])
			->addColumn('parent_id', 'integer', ['null' => true])
		    ->addColumn('title', 'string', ['limit' => 255])
		    ->addColumn('slug', 'string', ['limit' => 255])
		    ->addColumn('cell', 'string', ['limit' => 255, 'null' => true])
		    ->addColumn('text', 'text', ['null' => true])
		    ->addColumn('state', 'boolean', ['default' => 0])
		    ->addColumn('lft', 'integer', ['null' => true])
		    ->addColumn('rght', 'integer', ['null' => true])
		    ->addColumn('created', 'datetime', ['null' => true])
			->addColumn('modified', 'datetime', ['null' => true])
		    ->addColumn('options', 'text', ['null' => true])
		    ->addColumn('visibility', 'string', ['limit' => 11, 'default' => 'all'])
		    ->addColumn('visible_on', 'text', ['null' => true])
		    ->addIndex(['slug'], array('unique' => true, 'name' => 'platform_cells_slug_idx'))
		    //->addForeignKey('block_id', 'blocks', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
		    ->addIndex(['block_id', 'state'], array('unique' => false, 'name' => 'platform_cells_block_id_state_admin_idx'))
		    ->addIndex(['parent_id'], array('unique' => false, 'name' => 'platform_cells_parent_id_idx'))
		    ->addIndex(['visibility'], array('unique' => false, 'name' => 'platform_cells_visibility_idx'))
			->save();

	    $settings = $this->table('platform_settings');
	    $settings->addColumn('plugin', 'string', ['limit' => 64, 'default' => 'App'])
			->addColumn('path', 'string', ['limit' => 255])
		    ->addColumn('value', 'text', ['null' => true])
		    ->addIndex(['plugin', 'path'], array('unique' => true, 'name' => 'platform_settings_key_idx'))
			->save();

        //Create sessions table
	    $sessionsExists = $this->hasTable('sessions');
	    if (!$sessionsExists) {
		    $sessions =  $this->table('sessions',['id' => false, 'primary_key' => ['id']]);
		    $sessions->addColumn('id', 'string', ['limit' => 40])
			    ->addColumn('data', 'text', ['null' => true])
			    ->addColumn('expires', 'integer', ['null' => true])
			    ->save();
	    }

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
	    $this->dropTable('platform_blocks');
	    $this->dropTable('platform_cells');
	    $this->dropTable('platform_settings');
        $sessionsExists = $this->hasTable('sessions');
        if ($sessionsExists) {
            $this->dropTable('sessions');
        }

    }
}
