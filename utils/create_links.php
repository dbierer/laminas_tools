<?php
// use this to create links for laminas-tools if not done already
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
\Phpcl\LaminasTools\InstallFollowup::createSymlinks(VENDOR_DIR);
$list = glob(VENDOR_DIR . '/bin/*');
echo "vendor/bin folder contents:\n";
echo implode(PHP_EOL, $list) . PHP_EOL;