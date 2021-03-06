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
namespace Platform\Shell\Task;

use Bake\Shell\Task\BakeTask;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Utility\Hash;
use Platform\Core\Settings;
/**
 * Language string extractor
 *
 */
class SettingsTask extends BakeTask{

	public $plugin = null;

	public function startup() {
		parent::startup();

		if (isset($this->params['plugin'])) {
			$this->plugin = $this->params['plugin'];
		}
	}

    public function import(){
	    $settingsFile = 'settings';
		if(!empty($this->plugin)){
			$settingsFile = $this->plugin.'.'.$settingsFile;
			$settingsFileDir = Plugin::path($this->plugin).'config';
		} else {
			$settingsFileDir = ROOT.DS.'config';
		}
		$settingsDir = new Folder($settingsFileDir, false);
		if(!$settingsDir->find('settings.*')){
			return false;
		}

		$config = new PhpConfig();
        $data = $config->read($settingsFile);
		if(!$data){
			return false;
		}
		if(!empty($this->plugin)){
			$this->out(__d('platform', '- <success>Processing settings pool for {0} plugin</success>', $this->plugin));
		} else {
			$this->out(__d('platform', '- <success>Processing settings pool for {0}</success>', 'App'));
		}
	    $settingsTable = TableRegistry::get('Platform.Settings');
	    foreach ($data as $row){
		    if (!isset($row['plugin'])||empty($row['plugin'])) {
			    $row['plugin'] = $this->plugin;
		    }
			$data = [
				'plugin' => $row['plugin'],
			    'path' => $row['path'],
			];
			$setting = $settingsTable->find('all',[
					'conditions' => $data
				])
				->first();
			if(!$setting){
				$setting = $settingsTable->newEntity();
				$data = array_merge(
					$data,
					['value' => (isset($row['default']) ? $row['default'] : '')]
				);
			}
			$setting = $settingsTable->patchEntity($setting, $data);
		    $settingsTable->save($setting);
	    }
		//TODO: Maybe chared method for settings save
		$settings = $settingsTable->find()
			->combine('path', 'value')
			->toArray();
		ksort($settings);
		$settings = Hash::expand($settings);
		Settings::dump('config', 'default', $settings);
    }

    public function get(){
        $settingsTable = $this->getTableObject('Platform.Settings');
        $queryOptions = [];
        $queryOptions['conditions'] = ['Settings.plugin' => ''];
        if(!empty($this->plugin))
            $queryOptions['conditions'] = ['Settings.plugin' => $this->plugin];

        $settings = $settingsTable->find('extended', $queryOptions);
        $_settings = [];
        foreach($settings as $key=>$setting){
            $_settings[] = $setting->toArray();
        }
        return $_settings;
    }


    protected function cleanup($rows){
	    $rows = Hash::filter($rows);
        foreach($rows as $key=>$row){
            unset($rows[$key]['id'], $rows[$key]['value']);
        }
        return $rows;
    }

/**
 * Get a model object for a class name.
 *
 * @param string $className Name of class you want model to be.
 * @param string $table Table name
 * @return \Cake\ORM\Table Table instance
 */
    public function getTableObject($className) {
        if (TableRegistry::exists($className)) {
            return TableRegistry::get($className);
        }
        return TableRegistry::get($className, [
            'name' => $className,
            'table' => Inflector::tableize($className),
            'connection' => ConnectionManager::get($this->connection)
        ]);
    }

/**
 * Get the option parser for this task.
 *
 * This base class method sets up some commonly used options.
 *
 * @return \Cake\Console\ConsoleOptionParser
 */
    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->description(
            __d('cake_console', 'Task to dump/restore available settings.')
        )->addSubcommand('import', [
            'help' => __d('cake_console', 'Import settings from previously dumped files.')
        ]);//->addSubcommand('export', [
        //    'help' => __d('cake_console', 'Exporting available settings to dumps.')
        //]);
        return $parser;
    }

}
