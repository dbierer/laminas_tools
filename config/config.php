<?php
// config for Laminas MVC module creation tool

global $moduleName;
global $baseDir;
use Phpcl\LaminasTools\Constants;

// module template
$templates = [];
$templates['lam']['module'] = <<<EOT
<?php
declare(strict_types=1);
namespace $moduleName;
class Module
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
EOT;

// config file template
$templates['lam']['config'] = <<<EOT
<?php
declare(strict_types=1);
namespace $moduleName;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
return [
    'router' => [
        'routes' => [
            '$moduleName' => [
                'type'    => Segment::class,
                'options' => [
                    // add additional params to "route" key if needed
                    'route'    => '/{$moduleName}[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
EOT;
// route template
// MOD_SHORT == strtolower($modulename)
$templates['lam']['route'] = [
    'routeKey'     => "%%MOD_SHORT%%-%%SHORT_NAME%%",
    'routeType'    => 'Segment::class',
    'routeOpt'     => "/%%MOD_SHORT%%-%%SHORT_NAME%%[/:action]",
    'defController'=> Controller\IndexController::class,
    'defAction'    => 'index',
];

// controller template
$namespace = Constants::CONTROLLER_NAMESPACE;
$templates['lam']['controller'] = <<<EOT
<?php
declare(strict_types=1);
namespace {$moduleName}{$namespace};
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
EOT;

// view template
$templates['lam']['view'] = <<<EOT
<h1>$moduleName View</h1>
IndexController
EOT;

// factory template
$templates['lam']['factory'] = <<<EOT
<?php
namespace %%NAMESPACE%%;
use %%PSR_INTEROP%%\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
class %%CLASSNAME%% implements FactoryInterface
{
    /**
     * @param ContainerInterface \$container
     * @param string \$requestedName
     * @param null|array \$options
     * @return \$requestedName instance
     */
    public function __invoke(ContainerInterface \$container, \$requestedName, array \$options = null)
    {
        return new \$requestedName();
    }
}
EOT;

// controller-plugin template
$namespace = Constants::PLUGIN_NAMESPACE;
$templates['lam']['controller-plugin'] = <<<EOT
<?php
namespace {$moduleName}{$namespace};
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
class %%CLASSNAME%% extends AbstractPlugin
{
    /**
     * Produces mixed output depending on logic you place inside __invoke()
     *
     * @param string \$param
     * @param mixed \$default
     * @return mixed
     */
    public function __invoke(\$param = null, \$default = null)
    {
        \$item = \$default;
        // your code goes here
        return \$item;
    }
}
EOT;

// view-helper template
$namespace = Constants::HELPER_NAMESPACE;
$templates['lam']['view-helper'] = <<<EOT
<?php
namespace {$moduleName}{$namespace};
use Laminas\View\Helper\AbstractHtmlElement;
class %%CLASSNAME%% extends AbstractHtmlElement
{
    /**
     * Produces mixed output depending on logic you place inside __invoke()
     *
     * @param mixed \$param
     * @param mixed \$default
     * @return string \$item
     */
    public function __invoke(\$param = null, \$default = null)
    {
        \$item = \$default;
        // your code goes here
        return \$item;
    }
}
EOT;

// NOTE: any template elements with a "path" key will be added to a module
// Laminas MVC
$config['lam'] = [
    // base directory for the module
    'base' => $baseDir . '/module/' . $moduleName,
    // name of the file that registers modules
    'config' => $baseDir . '/config/modules.config.php',
    // function to insert the module name into file named above
    'insert' => function ($contents, $name) {
        if (strpos($contents, "'$name'") === FALSE)
            $contents = str_replace('];', "    '$name',\n];\n", $contents);
        return $contents;
    },
    // templates
    'templates' => [
        // all of these keys are added to a new module
        'module' => [
            'template' => $templates['lam']['module'],
            'path'  => '/src',
            'filename' => 'Module.php',
        ],
        'config' => [
            'template' => $templates['lam']['config'],
            'path'  => '/config',
            'filename' => 'module.config.php',
        ],
        'controller' => [
            'template' => $templates['lam']['controller'],
            'path'  => '/src/Controller',
            'filename' => 'IndexController.php',
        ],
        'controller-plugin' => [
            'template' => $templates['lam']['controller-plugin'],
            'path'  => '/src/Controller/Plugin',
            'prefix' => 'Controller\Plugin',
        ],
        'view' => [
            'template' => $templates['lam']['view'],
            'path'  => '/view/' . strtolower($moduleName) . '/index',
            'filename' => 'index.phtml',
        ],
        'view-helper' => [
            'template' => $templates['lam']['view-helper'],
            'path'  => '/src/View/Helper',
            'prefix' => 'View\Helper',
        ],
        // this key is used when creating a new controller
        'route' => [
            'template' => $templates['lam']['route'],
        ],
        // this key is used for creating generic factories
        'factory' => [
            'template' => $templates['lam']['factory'],
        ],
    ],
];
// Zend Framework 3
$config['zf3'] = $config['lam'];

// TODO:
/*
// Zend Framework 3
'zf2' => [
    'config' => $baseDir . '/config/application.config.php',
    'templates' => NULL,
],
// Mezzio
'mez' => [
    'config' => $baseDir . '/config/config.php',
    'templates' => NULL,
],
// Expressive
'exp' => [
    'config' => $baseDir . '/config/config.php',
    'templates' => NULL,
],
*/
return $config;
