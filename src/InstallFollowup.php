<?php
namespace Phpcl\LaminasTools;
use Composer\Script\Event;
/**
 * Used to followup on Composer installation
 */
class InstallFollowup
{
    public static function postInstall(Event $event)
    {
        // get directory for tools scripts
        $myDir = realpath(__DIR__ . '/..');
        // get "vendor" dir
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';
        $vendorBin = str_replace('//', '/', $vendorDir . '/bin');
        if (file_exists($vendorBin)) {
            $windows = PHP_WINDOWS_VERSION_MAJOR ?? FALSE;
            // if Windows, symlink to laminas-tools.bat
            if ($windows) {
                $toolExt = 'bat';
            } else {
                // otherwise, symlink to laminas-tools.sh
                $toolExt = 'sh';
            }
            // create symlink
            $fileToLinkFrom = $myDir . '/laminas-tools.' . $toolExt;
            $fileToLinkTo   = str_replace('//', '/', $vendorBin . '/phpcl-laminas-tools');
            symlink($fileToLinkFrom, $fileToLinkTo);
        }
    }
}