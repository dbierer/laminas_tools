<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class ControllerBuilder extends Base
{
    protected $controller;  // name of the controller to build

    /**
     * Creates everything needed for a Laminas/ZF3 controller
     *
     * @param string $baseDir == absolute path to project base
     * @param string $moduleName == name of module to build
     * @param string $controller == name of controller to build
     */
    public function build(string $baseDir, string $moduleName, string $controller)
    {

		// pull in config info
		$modPath   = $this->config['templates']['module']['path'];
		$ctlPath   = $this->config['templates']['controller']['path'];
		$ctlSuffix = Constants::SUFFIX['controller'];
		
		// create "short" names (for use in routes and view)
        $modShort  = strtolower($moduleName);
		$ctlShort  = strtolower(str_replace($ctlSuffix, '', $controller));

        // make base directory for controller
        $this->output .= 'Creating directory structures for new controller' . "\n";
        $ctlBase = $this->config['base'];

        // make sure mod structure exists
        if (!file_exists($ctlBase)) {
            $this->output .= Constants::ERROR_MOD . "\n";
            return FALSE;
        }

        // build controller base directory structure (if it doesn't exist)
        $dirs    = explode('/', $this->config['templates']['controller']['path']);
        foreach ($dirs as $dir) {
            $ctlBase = str_replace('//', '/', $ctlBase . '/' . $dir);
            if (!file_exists($ctlBase)) mkdir($ctlBase);
        }

        // get controller template
        $contents = $this->config['templates']['controller']['template'];
        // replace "IndexController" with $controller
        $contents = str_replace('IndexController', $controller, $contents);
        // write template contents out to appropriate file
        $this->output .= 'Writing out new controller file' . "\n";
        file_put_contents(str_replace('//', '/', $ctlBase . '/' . $controller . '.php'), $contents);

        // create view template
        $this->output .= 'Creating view template' . "\n";
        $viewPath = str_replace('\\\\', '/', $this->config['templates']['view']['path']);
        $viewDirs = explode('/', $viewPath);
        array_pop($viewDirs);
        array_push($viewDirs, $ctlShort);
        $viewPath = $this->config['base'] . '/' . implode('/', $viewDirs);
        $viewPath = str_replace('//', '/', $viewPath);
        if (!file_exists($viewPath)) mkdir($viewPath);

        // write out view template
        $viewFile = $viewPath . '/' . $this->config['templates']['view']['filename'];
        $viewFile = str_replace('//', '/', $viewFile);
        $contents = $this->config['templates']['view']['template'];
        $contents = str_replace('IndexController', $controller, $contents);
        file_put_contents($viewFile, $contents);

        // update module config file
        $this->output .= 'Backing up module config file' . "\n";
        $modConf = $this->config['base']
                 . '/' . $this->config['templates']['config']['path']
                 . '/' . $this->config['templates']['config']['filename'];
        $modConf = str_replace('//', '/', $modConf);
        copy($modConf, $modConf . '.bak');
        $this->output .= 'Updating module config file' . "\n";

        // inject controller registration
        $this->output .= 'Assigning new controller to "InvokableFactory"' . "\n";
        $key = $ctlSuffix . '\\' . $controller . '::class';
        $val = 'InvokableFactory::class';
        $contents = $this->injectConfig('controllers', 'factories', $key, $val, $modConf);

        // inject route
        $newRoute = $modShort . '-' . $ctlShort;
        $this->output .= sprintf('Adding route for new controller: %s', $newRoute) . "\n";
        $routeOpts = $this->config['templates']['route']['template'];
        $routeOpts = str_replace(['%%MOD_SHORT%%', '%%SHORT_NAME%%', '%%CTL_SUFFIX%%', '%%CONTROLLER%%'], 
								[$modShort, $ctlShort, $ctlSuffix, $controller], 
								$routeOpts);
        $contents = $this->injectConfig('router', 'routes', $newRoute, $routeOpts, $modConf, $contents, TRUE);
        // write out new config file
        return file_put_contents($modConf, $contents);
    }
}
