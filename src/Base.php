<?php
namespace Phpcl\LaminasTools;

/**
 * Base class used by *Builder class in this namespace
 */
class Base
{
    protected $module;      // name of the module
    protected $config;
    protected $output = '';
    /**
     * @param string $module == name of the module to be created or used
     * @param array $config == templates and how to inject module into list of modules for this app
     */
    public function __construct(string $module, array $config)
    {
        $this->module = $module;
        $this->config = $config;
    }
    /**
     * @return string $output + "\n"
     */
    public function getOutput()
    {
        return $this->output . PHP_EOL;
    }
}