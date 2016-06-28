<?php
namespace Platform\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Utility\Inflector;

class ViewPathComponent extends Component {

	public function fallback($views) {
		if (is_string($views)) {
			$views = [$views];
		}
		$controller = $this->_registry->getController();
		$templatePaths = $this->findTemplatePaths();
		foreach ($views as $view) {
			foreach ($templatePaths as $templatePath) {
                //TODO: Underscoring is under the question. Maybe optional for component
                $view = Inflector::underscore($view);
				$templatePath = $templatePath . $controller->viewBuilder()->templatePath() . DS . $view;
                //TODO: improve template extension detection to pass other template engine checks
				if (file_exists($templatePath . '.ctp')) {
					$controller->viewBuilder()->template($view);
                    $controller->render($view);
					return;
				}
			}
		}
	}

	public function findTemplatePaths() {
        $controller = $this->_registry->getController();

		$defaultViewPaths = App::path('Template');
		$pos = array_search(APP . 'Template' . DS, $defaultViewPaths);
		if ($pos !== false) {
			$viewPaths = array_splice($defaultViewPaths, 0, $pos + 1);
		} else {
			$viewPaths = $defaultViewPaths;
		}
        if ($plugin = $controller->viewBuilder()->plugin()) {
			$viewPaths = array_merge($viewPaths, App::path('Template', $plugin));
		}
		if ($theme = $controller->viewBuilder()->theme()) {
            foreach(App::path('Template', $theme) as $themePath){
                $viewPaths[] = $themePath;
    			if ($plugin) {
    				$viewPaths[] = $themePath . 'Plugin' . DS . $plugin . DS;
    			}
            }
		}
		$viewPaths = array_merge($viewPaths, $defaultViewPaths);
		return array_reverse(array_unique($viewPaths));
	}
}
