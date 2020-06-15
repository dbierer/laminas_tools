<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class Validate
{

    protected static $what = NULL;
    protected static $baseDir = NULL;
    protected static $moduleName = NULL;
    protected static $controller = NULL;
    protected static $message = NULL;
    protected static $inputs = [];

    /**
     * Returns validated CLI args as an array
     *
     * @return array [$what, $baseDir, $moduleName, $controller]
     */
    public static function getInputs()
    {
        return self::$inputs;
    }
    /**
     * Returns validation messages
     *
     * @return string $message
     */
    public static function getMessage()
    {
        return self::$message;
    }
    /**
     * Validates CLI args
     *
     * @param array $argv
     * @return bool TRUE == valid | FALSE otherwise
     */
    public static function checkInputs($argv)
    {
        // get params from command line
        $what       = $argv[1] ?? '';
        $baseDir    = $argv[2] ?? '';
        $name       = $argv[3] ?? '';

        // init validation vars
        $actual       = 0;    // actual valid args
        $expected     = 3;  // expected args
        $moduleName   = '';
        $className    = '';
        $mustSanitize = FALSE;
        $ctlNameParts = [];

        // error if $what not on list
        $what = strtolower($what);
        if (!isset(Constants::BUILD_WHAT[$what])) {
            self::$message .= sprintf(Constants::ERROR_WHAT, implode(',', array_keys(Constants::BUILD_WHAT))) . "\n";
        } else {
            // controller: extract module name
            if ($what == Constants::BUILD_WHAT['module']) {
                $moduleName = $name;
            } elseif ($what == Constants::BUILD_WHAT['factory']) {
                $nameParts  = explode('\\', $name);
                $moduleName = $nameParts[0];
                $className  = $name;
            } else {
                $nameParts  = explode('\\', $name);
                $moduleName = $nameParts[0];
                $className  = trim(array_pop($nameParts));
            }
            $actual++;
        }

        // if dir missing or doesn't exist, assume __DIR__
        if (empty($baseDir) || !file_exists($baseDir)) {
            self::$message .= Constants::ERROR_DIR . "\n";
        } else {
            $actual++;
        }

        // error if module name missing
        if (empty($moduleName)) {
            self::$message .= Constants::ERROR_MOD . "\n";
        } else {
            $actual++;
        }

        // sanitize class name for controller, controller-plugin or view-helper
        switch ($what) {
            case Constants::BUILD_WHAT['controller'] :
                $suffix   = Constants::SUFFIX['controller'];
                $errorMsg = Constants::ERROR_CTL_NM;
                $outMsg   = Constants::MOD_CTL_NM;
                $mustSanitize = TRUE;
                break;
            case Constants::BUILD_WHAT['controller-plugin'] :
                $suffix   = Constants::SUFFIX['controller-plugin'];
                $errorMsg = Constants::ERROR_PLG_NM;
                $outMsg   = Constants::MOD_PLG_NM;
                $mustSanitize = TRUE;
                break;
            case Constants::BUILD_WHAT['view-helper'] :
                $suffix   = Constants::SUFFIX['view-helper'];
                $errorMsg = Constants::ERROR_VHLP_NM;
                $outMsg   = Constants::MOD_VHLP_NM;
                $mustSanitize = TRUE;
                break;
            default ;
                $mustSanitize = FALSE;
        }

        // sanitize class name for controller, controller-plugin or view-helper
        if ($mustSanitize) {
            $expected++;
            if (empty($className)) {
                self::$message .= $errorMsg . "\n";
            } else {
                // sanitize controller/plugin/view-helper name
                $className = ucfirst($className);
                $className = str_replace($suffix, '', $className);
                if ($className == $suffix) {
                    self::$message .= $errorMsg . "\n";
                } else {
					$className .= $suffix;
                    self::$message .= sprintf($outMsg, $className) . "\n";
                    $actual++;
                }
            }
        }

        // store inputs
        self::$inputs = [$what, $baseDir, $moduleName, $className];
        return ($expected == $actual);
    }
}
