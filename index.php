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
    'Phpcl\LaminasTools\InstallFollowup' => __DIR__ . '/src/InstallFollowup.php',
];
spl_autoload_register(function ($class) use ($classMap) {
    if (isset($classMap[$class])) require_once $classMap[$class];
});

// use appropriate classes
use Phpcl\LaminasTools\{Constants,ModuleBuilder,ControllerBuilder,FactoryBuilder,Validate};

// init vars
$type       = '';
$success    = FALSE;
$what       = '';
$baseDir    = '';
$moduleName = '';
$controller = '';
$factory    = '';

if (Validate::checkInputs($argv)) {
    list($what, $baseDir, $moduleName, $controller, $factory) = Validate::getInputs();
} else {
    echo Validate::getMessage();
    echo Constants::USAGE;
    exit;
}

// pull in config
$config = require 'config/config.php';

// detect type
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
    // build module
    if ($what == Constants::BUILD_WHAT[0]) {
        $builder = new ModuleBuilder($moduleName, $config[$type]);
        switch ($type) {
            case 'zf3' :
            case 'lam' :
                $success = $builder->buildLamMvcModule($baseDir, $moduleName);
                break;
            default :
                echo Constants::ERROR_TYPE . "\n";
        }
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
    // build controller
    } elseif ($what == Constants::BUILD_WHAT[1]) {
        $builder = new ControllerBuilder($moduleName, $config[$type]);
        switch ($type) {
            case 'zf3' :
            case 'lam' :
                $success = $builder->buildLamMvcController($baseDir, $moduleName, $controller);
                break;
            default :
                echo Constants::ERROR_TYPE . "\n";
        }
        echo $builder->getOutput();
        if ($success) {
            printf(Constants::SUCCESS_MSG, $controller) . "\n";
        } else {
            printf(Constants::ERROR_UNABLE, $controller) . "\n";
        }
    // build factory
    } elseif ($what == Constants::BUILD_WHAT[2]) {
        $builder = new FactoryBuilder($moduleName, $config[$type]);
        switch ($type) {
            case 'zf3' :
            case 'lam' :
                $success = $builder->buildLamMvcFactory($baseDir, $factory);
                break;
            default :
                echo Constants::ERROR_TYPE . "\n";
        }
        echo $builder->getOutput();
    }
} catch (Throwable $t) {
    printf(Constants::ERROR_MSG, get_class($t), $t->getMessage(), $t->getTraceAsString()) . "\n";
}