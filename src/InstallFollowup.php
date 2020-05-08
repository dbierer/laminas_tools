<?php
namespace Phpcl\LaminasTools;
/**
 * Used to followup on Composer installation
 */
class InstallFollowup
{
    /**
     * Creates symbolic links needed to run the tool
     *
     * @param string $vendorDir == location of the "vendor" folder
     */
    public static function createSymlinks(string $vendorDir)
    {
        // get directory for tools scripts
        $myDir = realpath(__DIR__ . '/..');
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
            if (file_exists($to)) unlink($to);
            symlink($from, $to);
            // create symlink for phar file
            $from = $myDir . '/laminas-tools.phar';
            $to   = str_replace('//', '/', $vendorBin . '/phpcl-laminas-tools.phar');
            if (file_exists($to)) unlink($to);
            symlink($from, $to);
        }
    }
}