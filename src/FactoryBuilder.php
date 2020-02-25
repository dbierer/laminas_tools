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
     * @param string $factory == name of factory to build
     */
    public function buildLamMvcFactory(string $baseDir, string $factory)
    {
        // get factory template
        $contents = $this->config['templates']['factory']['template'];
        // get namespace
        $parts = explode('\\', $factory);
        $className = array_pop($parts);
        $namespace = implode('\\', $parts);
        // make sure $className has suffix "Factory"
        if (substr($className, -7) != 'Factory') $className .= 'Factory';
        // check to see if we need to use "Psr" or "Interop" \Container\ContainerInterface
        $psr = 'Psr';
        if (file_exists(self::FACT_INTERFACE_PATH)) {
            $interface = file_get_contents(self::FACT_INTERFACE_PATH);
            if (stripos($interface, 'Interop\Container\ContainerInterface')) {
                $psr = 'Interop';
            }
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