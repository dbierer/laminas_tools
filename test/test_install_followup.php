<?php
function getVendorDir()
{
    $dir = '';
    $single = DIRECTORY_SEPARATOR;
    $double = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
    $breakdown = explode($single, __DIR__);
    foreach ($breakdown as $key => $value) {
        if ($value == 'vendor') break;
        $dir .= $single . $value;
    }
    $dir = str_replace($double, $single, $dir . $single . 'vendor');
    return $dir;
}

// get vendor directory
define('VENDOR_DIR', getVendorDir());
require VENDOR_DIR . '/autoload.php';

try {
    $class = new class () extends \Composer\Script\Event {
    public function __construct()
    {
        /* do nothing */
    }
    // $event->getComposer()->getConfig()->get('vendor-dir');
    public function getComposer()
    {
        return new class () {
            public function getConfig()
            {
                return new class () {
                    public function get($arg)
                    {
                        $val = '';
                        if ($arg == 'vendor-dir') {
                            $val = VENDOR_DIR;
                        }
                        return $val;
                    }
                };
            }
        };
    }
    };
    \Phpcl\LaminasTools\InstallFollowup::postInstall($class);
} catch (Throwable $t) {
    echo get_class($t) . ':' . $t->getMessage() . PHP_EOL;
} finally {
    echo "You need to run 'composer require composer/composer' before running this test\n";
}

