<?php
/**
 * CLI runner for PHP-CL Laminas Tools
 * @TODO: rewrite a Laminas Service Manager Tool
 * See namespace Laminas\ServiceManager\Tool\ {FactoryCreator,FactoryCreatorCommand};
 */

// load composer autoloader + use appropriate classes
$classMap = [
    'Phpcl\LaminasTools\Base' => __DIR__ . '/src/Base.php',
    'Phpcl\LaminasTools\Validate' => __DIR__ . '/src/Validate.php',
    'Phpcl\LaminasTools\Constants' => __DIR__ . '/src/Constants.php',
    'Phpcl\LaminasTools\ModuleBuilder' => __DIR__ . '/src/ModuleBuilder.php',
    'Phpcl\LaminasTools\FactoryBuilder' => __DIR__ . '/src/FactoryBuilder.php',
    'Phpcl\LaminasTools\ControllerBuilder' => __DIR__ . '/src/ControllerBuilder.php',
    'Phpcl\LaminasTools\ControllerPluginBuilder' => __DIR__ . '/src/ControllerPluginBuilder.php',
    'Phpcl\LaminasTools\ViewHelperBuilder' => __DIR__ . '/src/ViewHelperBuilder.php',
    'Phpcl\LaminasTools\InstallFollowup' => __DIR__ . '/src/InstallFollowup.php',
];
spl_autoload_register(
    function ($class) use ($classMap) {
        if (isset($classMap[$class]))
            require_once $classMap[$class];
    }
);

// use appropriate classes
use Phpcl\LaminasTools\{
    Constants,
    ModuleBuilder,
    ControllerBuilder,
    ControllerPluginBuilder,
    ViewHelperBuilder,
    FactoryBuilder,
    Validate};

// init vars
$type       = '';
$success    = FALSE;
$what       = '';
$baseDir    = '';
$moduleName = '';
$className  = '';

if (Validate::checkInputs($argv)) {
    list($what, $baseDir, $moduleName, $className) = Validate::getInputs();
} else {
    echo Validate::getMessage();
    echo Constants::VERSION;
    echo Constants::USAGE;
    exit;
}

// pull in config
$config = require 'config/config.php';

// detect type (e.g. Laminas or ZF3)
foreach ($config as $key => $value) {
    if (file_exists($value['config'])) {
        $type = $key;
        break;
    }
}

if (empty($type)) {
    echo Validate::getMessage();
    echo Constants::ERROR_TYPE . "\n";
    exit;
}

try  {
    switch ($what) {
        // build module
        case Constants::BUILD_WHAT['module'] :
            $builder = new ModuleBuilder($moduleName, $config[$type]);
            $success = $builder->build($baseDir, $moduleName);
            echo $builder->getOutput();
            if ($success) {
                printf(Constants::SUCCESS_MSG, $moduleName) . "\n";
                echo "\n" . Constants::MOD_REMINDER . "\n";
                // run composer dump-autoload
                chdir($baseDir);
                if (file_exists('composer.phar')) {
                    shell_exec('php ' . $filename . ' dump-autoload');
                } else {
                    shell_exec('composer dump-autoload');
                }
                echo "\n";
            } else {
                printf(Constants::ERROR_UNABLE, $moduleName) . "\n";
            }
            break;
        // build controller
        case Constants::BUILD_WHAT['controller'] :
            $builder = new ControllerBuilder($moduleName, $config[$type]);
            $success = $builder->build($baseDir, $moduleName, $className);
            echo $builder->getOutput();
            if ($success) {
                printf(Constants::SUCCESS_MSG, $className) . "\n";
            } else {
                printf(Constants::ERROR_UNABLE, $className) . "\n";
            }
            break;
        // build controller plugin
        case Constants::BUILD_WHAT['controller-plugin'] :
            $builder = new ControllerPluginBuilder($moduleName, $config[$type]);
            $success = $builder->build($baseDir, $moduleName, $className);
            echo $builder->getOutput();
            if ($success) {
                printf(Constants::SUCCESS_MSG, $builder->getClassName()) . "\n";
            } else {
                printf(Constants::ERROR_UNABLE, $className) . "\n";
            }
            break;
        // build view helper
        case Constants::BUILD_WHAT['view-helper'] :
            $builder = new ViewHelperBuilder($moduleName, $config[$type]);
            $success = $builder->build($baseDir, $moduleName, $className);
            echo $builder->getOutput();
            if ($success) {
                printf(Constants::SUCCESS_MSG, $builder->getClassName()) . "\n";
            } else {
                printf(Constants::ERROR_UNABLE, $className) . "\n";
            }
            break;
        // build factory
        case Constants::BUILD_WHAT['factory'] :
            $builder = new FactoryBuilder($moduleName, $config[$type]);
            $success = $builder->build($baseDir, $className);
            echo $builder->getOutput();
            break;
        default :
            echo Validate::getMessage();
            echo Constants::ERROR_TYPE . "\n";
    }
} catch (Throwable $t) {
    printf(Constants::ERROR_MSG, get_class($t), $t->getMessage(), $t->getTraceAsString()) . "\n";
}
echo "\n";
