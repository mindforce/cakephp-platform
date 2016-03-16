<?php
namespace Platform\Shell;

use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Core\ConventionsTrait;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Utility\Inflector;
use Migrations\MigrationsDispatcher;
use Symfony\Component\Console\Input\ArgvInput;


class SetupShell extends Shell
{
    use ConventionsTrait;

    public $connection = 'default';

    /**
     * Defines constants that are required by phinx to get running
     *
     * @return void
     */
    public function initialize()
    {
        if (!defined('PHINX_VERSION')) {
            define('PHINX_VERSION', (0 === strpos('@PHINX_VERSION@', '@PHINX_VERSION')) ? '0.4.1' : '@PHINX_VERSION@');
        }
        parent::initialize();
    }

    /**
     * Assign $this->connection to the active task if a connection param is set.
     *
     * @return void
     */
    public function startup()
    {
        parent::startup();
        Configure::write('debug', true);
        Cache::disable();

        if (isset($this->params['connection'])) {
            $this->connection = $this->params['connection'];
        }
    }

    /**
     * Override main() to handle action
     *
     * @return mixed
     */
    public function main()
    {
        $connections = ConnectionManager::configured();
        if (empty($connections)) {
            $this->out('Your database configuration was not found.');
            $this->out('Add your database connection information to config/app.php.');
            return false;
        }
    }

    public function update(){
        $this->out(__d('platform', '-- Start database populating for app structure'));
        $this->hr();
        foreach(Plugin::loaded() as $plugin){
            $migrationsDir = new Folder(Plugin::path($plugin).'config'.DS.'Migrations', false);
            $migrationFiles = $migrationsDir->find('.*\.php');
            if(count($migrationFiles) > 0){
                $this->runMigrations($plugin);
                $this->out(__d('platform', '- {0} plugin migrations processed', $plugin));
            }
        }
        $migrationsDir = new Folder(ROOT.DS.'config'.DS.'Migrations', false);
        $migrationFiles = $migrationsDir->find('.*\.php');
        if(count($migrationFiles) > 0){
            $this->runMigrations();
            $this->out(__d('platform', '- App core database migrations processed'));
        }
        $this->hr();
    }

    protected function runMigrations($plugin = null){
        $args = ['migrations', 'migrate', '-q'];
        if($plugin){
            $args = array_merge($args, ['-p', $plugin]);
        }
        //debug($args);
        $app = new MigrationsDispatcher(PHINX_VERSION);
        $app->setAutoExit(false);
        // -q disable verbosing
        $input = new ArgvInput($args);
        $app->run($input);
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->description('Provide setup system'
        )->addSubcommand('install', [
            'help' => 'Run install instructions for whole setup process.',
            'default' => false,
        ])->addSubcommand('update', [
            'help' => 'Perform update instructions',
        ])->addSubcommand('reset', [
            'help' => 'Reset your setup to prvide install task again',
        ])->addOption('connection', [
            'help' => 'Database connection to use in conjunction with `all`.',
            'short' => 'c',
            'default' => 'default'
        ])->addOption('plugin', [
            'short' => 'p',
            'help' => 'Plugin to setup.'
        ]);
        /*
        foreach ($this->_taskMap as $task => $config) {
            $taskParser = $this->{$task}->getOptionParser();
            $parser->addSubcommand(Inflector::underscore($task), [
                'help' => $taskParser->description(),
                'parser' => $taskParser
            ]);
        }
        */

        return $parser;
    }

}
