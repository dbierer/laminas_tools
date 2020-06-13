<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class FactoryBuilder extends Base
{

    const FACT_INTERFACE_PATH = 'vendor/laminas/laminas-servicemanager/src/Factory/FactoryInterface.php';

    /**
     * Creates a generic Laminas/ZF3 factory
     *
     * @TODO: write output to a file
     * @param string $baseDir == path to project
     * @param string $className == name of factory to build
     */
    public function build(string $baseDir, string $factoryClass)
    {
        // get factory template
        $contents = $this->config['templates']['factory']['template'];
        // get namespace
        $parts = explode('\\', $factoryClass);
        $className = array_pop($parts);
        $namespace = implode('\\', $parts);
        // make sure $className has suffix "Factory"
        if (substr($className, -7) != 'Factory') $className .= 'Factory';
        // check to see if we need to use "Psr" or "Interop" \Container\ContainerInterface
        $psr = 'Psr';
        $checkFile = str_replace('//', '/', $baseDir . '/' . self::FACT_INTERFACE_PATH);
        if (file_exists($checkFile)) {
            $interface = file_get_contents($checkFile);
            if (stripos($interface, 'use Interop\Container') !== FALSE) {
                $psr = 'Interop';
            }
            unset($interface);
        }
        // replace variable elements
        $contents = str_replace('%%NAMESPACE%%', $namespace, $contents);
        $contents = str_replace('%%PSR_INTEROP%%', $psr, $contents);
        $contents = str_replace('%%CLASSNAME%%', $className, $contents);
        // save and let calling program get output
        $this->output = $contents;
        return TRUE;
    }
}
