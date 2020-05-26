<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class ControllerPluginBuilder extends Base
{
    protected $className;  // name of the plugin to build

    /**
     * Creates everything needed for a Laminas/ZF3 controller plugin
     *
     * @param string $baseDir == absolute path to project base
     * @param string $moduleName == name of module to build
     * @param string $className == name of plugin to build
     */
    public function build(string $baseDir, string $moduleName, string $className)
    {

        // create "alias" name
        $alias = strtolower(substr($className, 0, 1)) . substr($className, 1);
        $alias = str_replace(Constants::SUFFIX['controller-plugin'], '', $alias);

        // make base directory for plugin
        $this->output .= 'Creating directory structures for new plugin' . "\n";
        $ctlBase = $this->config['base'];

        // make sure mod structure exists
        if (!file_exists($ctlBase)) {
            $this->output .= Constants::ERROR_MOD . "\n";
            return FALSE;
        }

        // build plugin base directory structure (if it doesn't exist)
        $dirs    = explode('/', $this->config['templates']['controller-plugin']['path']);
        foreach ($dirs as $dir) {
            $ctlBase = str_replace('//', '/', $ctlBase . '/' . $dir);
            if (!file_exists($ctlBase)) mkdir($ctlBase);
        }

        // get plugin template
        $contents = $this->config['templates']['controller-plugin']['template'];
        // replace "%%CLASSNAME%%" with $className
        $contents = str_replace('%%CLASSNAME%%', $className, $contents);
        // write template contents out to appropriate file
        $this->output .= 'Writing out new plugin file' . "\n";
        file_put_contents(str_replace('//', '/', $ctlBase . '/' . $className . '.php'), $contents);
        // update module config file
        $this->output .= 'Backing up module config file' . "\n";
        $modConf = $this->config['base']
                 . '/' . $this->config['templates']['config']['path']
                 . '/' . $this->config['templates']['config']['filename'];
        $modConf = str_replace('//', '/', $modConf);
        copy($modConf, $modConf . '.bak');
        $this->output .= 'Updating module config file' . "\n";
        // inject plugin registration + alias
        $this->className = $this->config['templates']['controller-plugin']['prefix'] . '\\' . $className;
        $this->output .= 'Assigning new plugin to "InvokableFactory"' . "\n";
        $contents = $this->injectConfig(
            'controller_plugins',
            'factories',
            $this->className . '::class',
            'InvokableFactory::class',
            $modConf
        );
        $this->output .= "Assigning alias to new plugin: $alias\n";
        $contents = $this->injectConfig(
            'controller_plugins',
            'aliases',
            $alias,
            $this->className . '::class',
            $modConf
        );
        // write out new config file
        return file_put_contents($modConf, $contents);
    }
    /**
     * Returns built classname
     *
     * @return string $this->className
     */
    public function getClassName()
    {
        return $this->className;
    }
}