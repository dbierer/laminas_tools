<?php
require __DIR__ . '/vendor/autoload.php';
use Phpcl\LaminasTools\InstallFollowup;
use Composer\Script\Event;
$class = new class () extends Event {
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
                        $dir = '';
                        if ($arg == 'vendor-dir') {
                            $breakdown = explode(DIRECTORY_SEPARATOR, __DIR__);
                            var_dump($breakdown);
                            foreach ($breakdown as $key => $value) {
                                if ($value == 'vendor') break;
                                $dir .= DIRECTORY_SEPARATOR . $value;
                            }
                            $dir .= DIRECTORY_SEPARATOR . 'vendor';
                        }
                        return $dir;
                    }
                };
            }
        };
    }
};
InstallFollowup::postInstall($class);