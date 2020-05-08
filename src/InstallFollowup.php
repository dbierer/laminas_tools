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
            $windows = (stripos(PHP_OS, 'WINNT') !== FALSE);
            // if Windows, symlink to laminas-tools.bat
            if ($windows) {
                $toolExt = 'bat';
            } else {
                // otherwise, symlink to laminas-tools.sh
                $toolExt = 'sh';
            }
            // create symlink for tools script (*.sh or *.bat)
            $from = $myDir . '/laminas-tools.' . $toolExt;
            $to   = str_replace('//', '/', $vendorBin . '/phpcl-laminas-tools');
            unlink($to);
            symlink($from, $to);
            // create symlink for phar file
            $from = $myDir . '/laminas-tools.phar';
            $to   = str_replace('//', '/', $vendorBin . '/phpcl-laminas-tools.phar');
            unlink($to);
            symlink($from, $to);
        }
    }
}