<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class Constants
{
    const VERSION = 'Version: 1.0.5' . "\n";
    const COMPOSER_JSON = 'composer.json';
    const USAGE = 'Usage:   phpcl-laminas-tools WHAT PATH NAME' . "\n"
                . '    WHAT = module | controller | factory | controller-plugin | view-helper' . "\n"
                . '    PATH = absolute path to base of your project structure' . "\n"
                . '    NAME = name of the new module to create or ' . "\n"
                . '           class name of the controller (e.g. "Test\\\Controller\\\ListController") or ' . "\n"
                . '           class name of the generic factory (e.g. "Test\\\Service\\\CoolServiceFactory")' . "\n"
                . '    More information: https://github.com/phpcl/laminas_tools' . "\n";

    const BUILD_WHAT   = [
        'factory'           => 'factory',
        'module'            => 'module',
        'controller'        => 'controller',
        'view-helper'       => 'view-helper',
        'controller-plugin' => 'controller-plugin',
    ];
    const SUFFIX       = [
        'controller'        => 'Controller',
        'view-helper'       => 'Helper',
        'controller-plugin' => 'Plugin'
    ];
    const CONTROLLER_NAMESPACE = '\Controller';
    const PLUGIN_NAMESPACE     = '\Controller\Plugin';
    const HELPER_NAMESPACE     = '\View\Helper';
    const ERROR_MODCFG = 'ERROR: unable to locate module config file';
    const ERROR_WHAT   = 'ERROR: "WHAT" param needs to be one of %s';
    const ERROR_DIR    = 'ERROR: missing or invalid directory path given';
    const ERROR_MOD    = 'ERROR: missing module name or module structure does not exist';
    const ERROR_CTL    = 'ERROR: missing controller name';
    const ERROR_CTL_NM = 'ERROR: controller name must be in this form: ' . "\n" . '       "Abc\Controller\XyzController" where "Abc" is the module name and "Xyz" is descriptive of the functionality';
    const ERROR_PLG_NM = 'ERROR: controller plugin name must be in this form: ' . "\n" . '       "Abc\Controller\Plugin\XyzPlugin" where "Abc" is the module name and "Xyz" is descriptive of the functionality' . "\n" . 'NOTE: "xyz" becomes the alias.  Make sure it is unique!';
    const ERROR_VHLP_NM= 'ERROR: view helper name must be in this form: ' . "\n" . '       "Abc\View\Helper\XyzHelper" where "Abc" is the module name and "Xyz" is descriptive of the functionality' . "\n" . 'NOTE: "xyz" becomes the alias.  Make sure it is unique!';
    const ERROR_TYPE   = 'ERROR: unable to detect framework type';
    const ERROR_UNABLE = 'ERROR: unable to create %s';
    const ERROR_MSG    = 'ERROR: %s : %s' . PHP_EOL . '%s';
    const SUCCESS_MSG  = 'SUCCESS: %s created!';
    const MOD_CTL_NM   = 'Using "%s" for controller name';
    const MOD_PLG_NM   = 'Using "%s" for controller plugin name';
    const MOD_VHLP_NM  = 'Using "%s" for view helper name';
    const MOD_REMINDER = 'You need to run the command "composer dump-autoload" (or "php composer.phar dump-autoload") to have the new module recognized';
}
