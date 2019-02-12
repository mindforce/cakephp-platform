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
namespace Platform\Event;

use Cake\Event\EventListenerInterface;
use Cake\Core\Plugin;
use Cake\Utility\Inflector;
use Cake\Core\Configure;
use Cake\Log\Log;

class CoreEvent implements EventListenerInterface {

    public function implementedEvents() {
        return array(
            'Controller.initialize' => array(
                'callable' => 'onControllerInit'
            ),
            'View.beforeLayout' => [
                'callable' => 'setDefaultViewAssets'
            ]
        );
    }

    public function onControllerInit($event) {
        $controller = $event->getSubject();
        $controller->loadComponent('Platform.ViewPath');
    }

    public function setDefaultViewAssets($event){
        $view = $event->getSubject();
        $params = $view->request->params;
        $path = Configure::read('App.webroot') . DS . '{asset}' . DS;
        $path .= (!empty($params['prefix']) ? Inflector::underscore($params['prefix']) . DS : '');
        $path .= Inflector::underscore($params['controller']). DS . Inflector::underscore($params['action']);
        if (isset($params['plugin'])&&!empty($params['plugin'])){
            $path = Plugin::path($params['plugin']). $path;
        } else {
            $path = ROOT . DS . $path;
        }
        $assetBase = (!empty($params['plugin']) ? $params['plugin'].'.' : '');
        $assetBase .= (!empty($params['prefix']) ? Inflector::underscore($params['prefix']) . '/' : '');
        $assetBase .= Inflector::underscore($params['controller']).'/'.Inflector::underscore($params['action']);
        $cssPath = str_replace('{asset}', str_replace('/', '', Configure::read('App.cssBaseUrl')), $path).'.css';
        if(file_exists($cssPath)) {
            $view->Html->css($assetBase.'.css', ['block' => true]);
        }
        $jsPath = str_replace('{asset}', str_replace('/', '', Configure::read('App.jsBaseUrl')), $path).'.js';
        if(file_exists($jsPath)) {
            $view->Html->script($assetBase.'.js', ['block' => true]);
        }
    }

}
